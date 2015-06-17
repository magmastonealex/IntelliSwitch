import redis

class WebDevice:

	def __init__(self, ky):
		self.key=ky
		self.redis=redis.StrictRedis(host='squirrel.home', port=6379, db=0)

	def inputs(self): # takes nothing, returns a list of input statuses.
		return [int(self.redis.get(self.key))]

