

#signal source specifier: i:34:0 or i:34:1
# On the bus, this really needs to be an abstract class somewhere considering it's use.
class STDODevice:
	prnt=0
	def __init__(self, address):
		self.ad=address
	def inputs(self): # takes nothing, returns a list of input statuses.
		return [0]
	def setOutput(self,outputPin,outputState): # takes a pin, and a state, and sets it.
		if(self.prnt!=outputState):
			print str(outputPin)+""+str(outputState)
			self.prnt=outputState
	def toggleOutput(self,outputPin):
		print str(outputPin)+" toggled"
