/*
  rftester.cpp
  by Sarrailh Remi

  Description : This program display a radio code
*/

#include "RCSwitch.h"
#include <stdlib.h>
#include <stdio.h>


  RCSwitch mySwitch;

  int main(int argc, char *argv[]) {

   int pin;
   int received_code;
   int ok_code = 0;
   int wait_cycles = 100;

   if(argc == 2) //Verify if there is an argument
   {
      pin = atoi(argv[1]); //Convert first argument to INT
      //printf("PIN SELECTED :%i\n",pin);
    }
  else
  {
    printf("No PIN Selected\n");
    exit(1);
  }


/* IS WIRING PI INSTALLED ? */

    if(wiringPiSetup() == -1) //Initialize WiringPI
    {
      printf("WIRING PI NOT INSTALLED");
      exit(1);
    }

    //We received GPIO State to see if something is happening
    for (int timer = 0; timer < 100; ++timer)
    {
      received_code = digitalRead(pin);
      if (received_code == 1) { ok_code++; } //If PIN IS HIGH add to ok_code
      //printf("%i",digitalRead(pin));
      delay(10);
    }
    
    if (ok_code == 0) //If the PIN was never on HIGH we assume there was a problem
    {
      printf("Wrong GPIO\n");
      exit(1);
    }
    else //If test1 PASS
{      

/* IS PIN RECEIVING RCSWITCH DATA */
   mySwitch = RCSwitch(); //Settings RCSwitch
   mySwitch.enableReceive(pin);

   
   int i = 0;
   while(i < wait_cycles) { //Waiting for Data
    i++;
    if (mySwitch.available()) { //If Data is present

      unsigned long code = mySwitch.getReceivedValue(); //Save Data in value
      unsigned int protocol = mySwitch.getReceivedProtocol();
      printf("%i:%ld\n", protocol,code ); //Show Code in Decimal
      break;   
    }
	

  }
  exit(0);
}
}
