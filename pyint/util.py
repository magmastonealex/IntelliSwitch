from i2c import I2CDevice
from threading import Lock
#i:34:1
class InputManager:
	devices={}
	def addDevice(self, deviceIdent):
		self.devices[deviceIdent]=[]
		self.devices[deviceIdent].append(Lock())
		self.devices[deviceIdent].append(I2CDevice(deviceIdent))
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
	print test.getInputs()