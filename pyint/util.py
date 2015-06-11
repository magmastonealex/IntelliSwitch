from i2c import I2CDevice
from print_handler import STDODevice
from redis_handler import WebDevice
from threading import Lock
#i:34:1
class InputManager:
	devices={}
	def addDevice(self, deviceIdent):
		deviceI=deviceIdent.split("-")[0]
		self.devices[deviceI]=[]
		self.devices[deviceI].append(Lock())
		if deviceI[0]=="i":
			self.devices[deviceI].append(I2CDevice(int(deviceI.split(":")[1],16)))
		elif deviceI[0]=="w":
			self.devices[deviceI].append(WebDevice(deviceI.split(":")[1]))
		elif deviceI[0]=="p":
			self.devices[deviceI].append(STDODevice(deviceI.split(":")[1]))
		elif deviceI[0]=="t":
			self.devices[deviceI].append(tempDevice(deviceI.split(":")[1]))
	def getInputs(self):
		inps={}
		for k,v in self.devices.iteritems():
			v[0].acquire()
			inps[k]=v[1].inputs()
			v[0].release()
		return inps


if __name__ == "__main__": # Do some quick checks.
	test=InputManager()
	test.addDevice(0x36)
	test.addDevice(0x35)
	test.addDevice(0x34)
	print test.getInputs()