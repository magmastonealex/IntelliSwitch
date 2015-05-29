#include <avr/io.h>
#include "usitwislave.h"
static void twi_callback_2(uint8_t input_buffer_length, const uint8_t *input_buffer, uint8_t *output_buffer_length, uint8_t *output_buffer){

	output_buffer[0]=0xDE;
	output_buffer[1]=0xAD;
	output_buffer[2]=0xBE;
	output_buffer[3]=0xEF;
}

int main(){
	usi_twi_slave(0x34, 0, twi_callback_2,0);
	while(1){


	}
}

