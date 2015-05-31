from Adafruit_I2C import Adafruit_I2C
import time

#signal source specifier: i:34:0 or i:34:1

class I2CDevice:
	statusChecker=0x00 # keep a record of the data that's being sent

	def __init__(self, address):
		self.device=Adafruit_I2C(address)

	def inputs(self): # takes nothing, returns a list of input statuses.
		dat=self.device.readU8(self.statusChecker)
		toret=[]
		toret.append( 1 if(dat & (1<<0)) else 0)
		toret.append( 1 if(dat & (1<<1)) else 0)
		toret.append( 1 if(dat & (1<<2)) else 0)
		toret.append( 1 if(dat & (1<<3)) else 0)
		return toret
	def setOutput(self,outputPin,outputState): # takes a pin, and a state, and sets it.
		if outputState == 1:
			self.statusChecker = self.statusChecker | (1<<outputPin)
		else:
			self.statusChecker = self.statusChecker & ~(1<<outputPin)
		self.device.readU8(self.statusChecker)
	def toggleOutput(self,outputPin):
		self.statusChecker = self.statusChecker ^ (1<<outputPin)
		self.device.readU8(self.statusChecker)


if __name__ == "__main__": # Do some quick checks.
	test=I2CDevice(0x34)
	print test.inputs()