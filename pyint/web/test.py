import time

x_rate  =  6 #ticks/step
x_steps = 10000
x_cur=1

y_rate  =  1 # ticks/step
y_steps = 30000
y_cur=1


def step_x():
	print "X"

def step_y():
	print "Y"


def cnc_move():
	global x_rate
	global x_steps
	global x_cur
	global y_rate
	global y_steps
	global y_cur

	if x_cur == x_rate and x_steps > 0:
		x_cur=1
		step_x()
		x_steps=x_steps-1
	else:
		x_cur=x_cur+1
	if y_cur == y_rate and y_steps > 0:
		y_cur=1
		step_y()
		y_steps=y_steps-1
	else:
		x_cur=x_cur+1

while 1:
	cnc_move()
	time.sleep(0.01)
