#include <avr/io.h>
#include "usitwislave.h"


#define STATE_PINA0 (PINA&(1<<PA0))
#define STATE_PINA1 (PINA&(1<<PA1))


static void twi_callback_2(uint8_t input_buffer_length, const uint8_t *input_buffer, uint8_t *output_buffer_length, uint8_t *output_buffer){
	if(input_buffer_length > 0){
		if(input_buffer[0]&0x01){ // Read address!
			PORTB |= (1<<PB0);
		}else{
			PORTB &= ~(1<<PB0);
		}
		if(input_buffer[0]&0x02){
			PORTB |=(1<<PB1);
		}else{
			PORTB &= ~(1<<PB1);
		}
	}
	uint8_t stte=0x00;

	if(PINA&(1<<PA0)){
		stte |= (1<<0);
	}
	if(PINA&(1<<PA1)){
		stte |= (1<<1);
	}

	if(PINA&(1<<PA2)){
		stte |= (1<<2);
	}

	if(PINA&(1<<PA3)){
		stte |= (1<<3);
	}

	output_buffer[0]= stte;
	*output_buffer_length=1;

}

int main(){
	DDRB |= ((1<<PB0) | (1<<PB1));
	PORTB &= ~((1<<PB0)| (1<<PB1));

	DDRA &= ~((1<<PA0)|(1<<PA1)|(1<<PA2)|(1<<PA3));
	PORTA |= ((1<<PA0)|(1<<PA1)|(1<<PA2)|(1<<PA3));

	usi_twi_slave(0x36, 0, twi_callback_2,0);
	while(1){
			//test

	}
}

