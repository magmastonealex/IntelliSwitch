from util import InputManager
from threading import Lock,Thread
from pubsub import pub
import time
import json

t=InputManager()
t.addDevice(0x36)
t.addDevice(0x35)
t.addDevice(0x34)

#i:34:1

class threeway:
	def update_state(self,inputs):
		if self.state_a != inputs[self.devIDA][self.posA]:
			self.state_a=inputs[self.devIDA][self.posA]
			self.output[0].acquire()
			self.output[1].toggleOutput(self.outputPin)
			print "TOG"
			self.output[0].release()
		if self.state_b != inputs[self.devIDB][self.posB]:
			self.state_b=inputs[self.devIDB][self.posB]
			self.output[0].acquire()
			self.output[1].toggleOutput(self.outputPin)
			print "TOG"
			self.output[0].release()


	def __init__(self,input_1, input_2,affect):
		global t
		self.devIDA=int(input_1.split(":")[1],16)
		self.devIDB=int(input_2.split(":")[1],16)
		self.posA=int(input_1.split(":")[2])
		self.posB=int(input_2.split(":")[2])

		self.output=t.devices[int(affect.split(":")[1],16)]
		self.outputPin=int(affect.split(":")[2])

		inps=t.getInputs()

		self.state_a=inps[self.devIDA][self.posA]
		self.state_b=inps[self.devIDB][self.posB]
		print self.state_a
		print self.state_b

class direct:
	def update_state(self,inputs):
		if inputs[self.devIDA][self.posA] == 1 and self.state ==0:
			self.output[0].acquire()
			self.output[1].setOutput(self.outputPin,1)
			self.output[0].release()
			self.state=1
			print "Set 1"
		elif inputs[self.devIDA][self.posA] == 0 and self.state ==1:
			self.output[0].acquire()
			self.output[1].setOutput(self.outputPin,0)
			self.output[0].release()
			self.state=0
			print "Set 0"
	def __init__(self,input_1,affect):
		global t
		self.state=0
		self.devIDA=int(input_1.split(":")[1],16)
		self.posA=int(input_1.split(":")[2])
		self.output=t.devices[int(affect.split(":")[1],16)]
		self.outputPin=int(affect.split(":")[2])


descriptor='[{"function":"direct","inputs":["i:36:1"],"outputs":["i:35:0"]},{"function":"direct","inputs":["i:35:1"],"outputs":["i:34:0"]}]'
#descriptor='[{"function":"threeway","inputs":["i:35:1","i:36:1"],"outputs":["i:35:0"]},{"function":"threeway","inputs":["i:35:1","i:36:1"],"outputs":["i:34:0"]}]'
descriptor_obj=json.loads(descriptor)
funcs=[]
for desc in descriptor_obj:
	func=None
	if desc["function"]=="threeway":
		func=threeway(desc["inputs"][0],desc["inputs"][1],desc["outputs"][0])
		print "Created 3way Switch"
	elif desc["function"]=="direct":
		func=direct(desc["inputs"][0],desc["outputs"][0])
		print "Created Direct Connection"
	funcs.append(func)
	pub.subscribe(func.update_state, 'new_inputs')

while 1:
	pub.sendMessage('new_inputs', inputs=t.getInputs())
	time.sleep(0.1)