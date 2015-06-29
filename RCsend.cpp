/*
RCsend permet d'envoyer des codes radios en 433Mhz
Elle est basé sur le portage de la bibliothèqe RCSwitch et de HomeEasy
sur le Raspberry Pi

Repo Github
https://github.com/maditnerd/UltimateRCswitch


Usage (RCswitch)   : RCsend GPIO Protocole:Code
Usage (chacon)     : RCsend GPIO Protocole:Telecommande:Bouton:Etat
Exemple (RCswitch) : RCsend 0 1:1234
Exemple (Chacon)   : RCsend 0 4:1234:1:1

 */

#include "RCSwitch.h"
#include <stdlib.h>
#include <stdio.h>
#include <iostream>
#include <vector>
#include <string>
 
 using namespace std;
 vector<string> explode( const string &delimiter, const string &str);


#define RCSWITCH1 1 // Default protocol, should works with must stuff
#define RCSWITCH2 2 // I never used Protocol 2 so I don't know what it is about 
#define RCSWITCH3 3 // Protocol 3 is missing from original library
#define CHACON 4
#define VENUS 5 //Venus Low Cost Switch for Radio Controlled Lamp

 unsigned int PIN;
 unsigned long code;
 unsigned long device;
 unsigned int button;
 unsigned int bits = 8;
 unsigned int protocol;
 string onoff_string;
 bool onoff = true;

 int main(int argc, char *argv[]) {

    // Consult https://projects.drogon.net/raspberry-pi/wiringpi/pins/
    // for more information.
    // Parse the two parameter to this command as an integer

  if (argc==3)  { //Verify if there is 2 arguments
    PIN = atoi(argv[1]); //First Argument is Transmitter Pin
    string code_string = argv[2]; 
    vector<string> code_array = explode(":",code_string);


    //for(int i=0; i<code_array.size(); i++){
    //  cout <<i << " ["<< code_array[i] <<"] " <<endl;
    //}



    if (wiringPiSetup () == -1) return 13;
    RCSwitch radio = RCSwitch();
    radio.enableTransmit(PIN);

    protocol = atoi(code_array[0].c_str());




  /*
  
RCSWITCH PROTOCOL 1/2/3/4 (Default RCSwitch)

   */



/*

RCSWITCH PROTOCOL 5 (Home Easy)

 */



if(protocol == CHACON){
  device = atol(code_array[1].c_str());
  button = atoi(code_array[2].c_str());
  onoff = atoi(code_array[3].c_str());

  printf("Prise Chacon ---- Télécomande:%ld Bouton:%i Etat:%i\n",device,button,onoff);
  radio.send(device, button, onoff);
}
else{
  code = atol(code_array[1].c_str());
  printf("Prise RCSWITCH ---- Protocole:%i Code:%ld\n",protocol,code);
  //Calculate size of Binary Message
  if (code > 256)
  {
    bits = 16;
  }
  if (code > 65536)
  {
    bits = 24;
  }
  if (code > 16777216)
  {
    bits = 32;
  }

  radio.setProtocol(protocol);
  radio.send(code, bits);
}


}


else {
 printf("Pas assez de paramètres\n");
 printf("Usage: RCsend GPIO Protocole:Code \n");
 printf("Usage: RCsend GPIO Protocole:Telecommande:Bouton:Etat \n");
 printf("Exemple (RCswitch): RCsend 0 1:1234 \n");
 printf("Exemple (Chacon): RCsend 0 4:1234:1:1 \n");
}

return 0;


}

vector<string> explode( const string &delimiter, const string &str)
{
  vector<string> arr;

  int strleng = str.length();
  int delleng = delimiter.length();
  if (delleng==0)
        return arr;//no change

      int i=0;
      int k=0;
      while( i<strleng )
      {
        int j=0;
        while (i+j<strleng && j<delleng && str[i+j]==delimiter[j])
          j++;
        if (j==delleng)//found delimiter
        {
          arr.push_back(  str.substr(k, i-k) );
          i+=delleng;
          k=i;
        }
        else
        {
          i++;
        }
      }
      arr.push_back(  str.substr(k, i-k) );
      return arr;
}
