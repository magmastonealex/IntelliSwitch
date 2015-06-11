from util import InputManager
from threading import Lock,Thread
from pubsub import pub
import time
import redis
import SimpleHTTPServer
import json

import os

os.chdir("web")

t=InputManager()
#t.addDevice(0x36)
#t.addDevice(0x35)
#t.addDevice(0x34)

t.addDevice('p:30-1')
t.addDevice('w:40-0')
#i:34:1

class orclass:
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


	def __init__(self,inputs,affect):
		global t
		input_1=inputs[0]
		input_2=inputs[1]
		self.devIDA=input_1.split("-")[0]
		self.devIDB=input_2.split("-")[0]
		self.posA=int(input_1.split("-")[1])
		self.posB=int(input_2.split("-")[1])

		self.output=t.devices[affect.split("-")[0]]
		self.outputPin=int(affect.split("-")[1])

		inps=t.getInputs()

		self.state_a=inps[self.devIDA][self.posA]
		self.state_b=inps[self.devIDB][self.posB]
		print self.state_a
		print self.state_b

class andclass:
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


	def __init__(self,inputs,affect):
		global t
		input_1=inputs[0]
		input_2=inputs[1]
		self.devIDA=input_1.split("-")[0]
		self.devIDB=input_2.split("-")[0]
		self.posA=int(input_1.split("-")[1])
		self.posB=int(input_2.split("-")[1])

		self.output=t.devices[affect.split("-")[0]]
		self.outputPin=int(affect.split("-")[1])

		inps=t.getInputs()

		self.state_a=inps[self.devIDA][self.posA]
		self.state_b=inps[self.devIDB][self.posB]
		print self.state_a
		print self.state_b


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


	def __init__(self,inputs,affect):
		global t
		input_1=inputs[0]
		input_2=inputs[1]
		self.devIDA=input_1.split("-")[0]
		self.devIDB=input_2.split("-")[0]
		self.posA=int(input_1.split("-")[1])
		self.posB=int(input_2.split("-")[1])

		self.output=t.devices[affect.split("-")[0]]
		self.outputPin=int(affect.split("-")[1])

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
	def __init__(self,inputs,affect):
		global t
		input_1=inputs[0]
		self.state=0
		self.devIDA=input_1.split("-")[0]
		self.posA=int(input_1.split("-")[1])
		self.output=t.devices[affect.split("-")[0]]
		self.outputPin=int(affect.split("-")[1])


#descriptor='[{"function":"direct","inputs":["i:36-1"],"outputs":["i:35-1"]},{"function":"direct","inputs":["i:35-1"],"outputs":["i:34-0"]}]'
#descriptor='[{"function":"threeway","inputs":["i:35:1","i:36:1"],"outputs":["i:35:0"]},{"function":"threeway","inputs":["i:35:1","i:36:1"],"outputs":["i:34:0"]}]'
descriptor='[{"function":"direct","inputs":["w:40-0"],"outputs":["p:30-1"]}]'

kill=False
def main(descriptr):
	global kill
	descriptor_obj=json.loads(descriptr)
	funcs=[]
	for desc in descriptor_obj:
		func=None
		if desc["function"]=="threeway":
			func=threeway(desc["inputs"],desc["outputs"][0])
			print "Created 3way Switch"
		elif desc["function"]=="direct":
			func=direct(desc["inputs"],desc["outputs"][0])
			print "Created Direct Connection"
		funcs.append(func)
		pub.subscribe(func.update_state, 'new_inputs')
	while kill==False:
		pub.sendMessage('new_inputs', inputs=t.getInputs())
		time.sleep(0.1)
	for func in funcs:
		pub.unsubscribe(func.update_state, 'new_inputs')
		del func
reds=redis.StrictRedis(host='squirrel.home', port=6379, db=0)
trd=None
while True: # Keep cheching for descriptor updates. If there is one, then kill off the old main and make a new one. 
	x=reds.get("descriptor")
	if x != descriptor:
		descriptor=x
		kill=True
		time.sleep(1)
		trd=Thread(target=main, args=(descriptor,))
		reds.set("current","true")
	time.sleep(1)