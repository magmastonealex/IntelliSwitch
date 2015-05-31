from util import InputManager
from threading import Lock,Thread
from pubsub import pub
import time

t=InputManager()
t.addDevice(0x36)
t.addDevice(0x35)

#i:34:1

class threeway_switch:
	def update_state(self,inputs):
		print inputs
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



x=threeway_switch("i:35:1","i:36:1","i:35:0")


pub.subscribe(x.update_state, 'new_inputs')
while 1:
	pub.sendMessage('new_inputs', inputs=t.getInputs())
	time.sleep(0.1)