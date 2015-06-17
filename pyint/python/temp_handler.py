from redis_handler import WebDevice # this is the hack to end all hacks. Deal with Redis directly.

class tempDevice:
	prnt=0
	value=0
	def __init__(self, address):
		self.ad=address
		self.wd = WebDevice("weather:temp")
	def inputs(self): # takes nothing, returns a list of input statuses.
		if self.prnt % 1000 == 0: # every 100 seconds (ticks are 0.1s) 
			self.value=self.wd.inputs()[0]
		if self.value => int(self.ad.split("-")[1]):
			return [1]
		else:
			return [0]