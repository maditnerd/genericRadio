# genericRadio
Allumer et éteindre des prises radio 433 génériques (SCS/Pheonix) et Chacon (HomeEasy)  avec Yana Server

# Installation
Dans yana aller sur le market et chercher "generic Radio".

# Permissions
Suivez les instructions dans les Préférences pour régler les permissions.

# Branchement
![Branchement](https://raw.githubusercontent.com/maditnerd/genericRadio/master/img/branchement.jpg)

# Prises compatibles
Il est possible d'importer des prises (et de les exporter) au format JSON.
Voici les prises compatibles avec ce plugin:

## Chacon
Les prises chacon ont un code spécifique pour chaque télécommande.
Il vous faut donc le récuperer par vous même.

## SCS 3063
[Détails / Liens](http://www.scs-laboutique.com/kit+3+prises+telecommandees+3063+f+3600w-566)

![SCS3063](https://raw.githubusercontent.com/maditnerd/genericRadio/master/img/scs3063.jpg)
Importer
```json
[{"name":"I1","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1381716","radiocodeOff":"1:1381717"},{"name":"I2","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1394005","radiocodeOff":"1:1394004"},{"name":"I3","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1397077","radiocodeOff":"1:1397076"},{"name":"I4","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1397845","radiocodeOff":"1:1397844"},{"name":"II1","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4527445","radiocodeOff":"1:4527444"},{"name":"II2","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4539733","radiocodeOff":"1:4539732"},{"name":"II3","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4542805","radiocodeOff":"1:4542804"},{"name":"II4","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4543573","radiocodeOff":"1:4543572"},{"name":"III1","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5313876","radiocodeOff":"1:5313877"},{"name":"III2","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5326164","radiocodeOff":"1:5326165"},{"name":"III3","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5329236","radiocodeOff":"1:5329237"},{"name":"III4","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5330004","radiocodeOff":"1:5330005"},{"name":"IV1","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5510485","radiocodeOff":"1:5510484"},{"name":"IV2","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5522773","radiocodeOff":"1:5522772"},{"name":"IV3","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5525845","radiocodeOff":"1:5525844"},{"name":"IV4","description":"SCS-3063","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5526613","radiocodeOff":"1:5526612"]
```

## SCS S316
[Détails / Liens](http://www.scs-laboutique.com/kit+2+prises+telecommandees+s316+2+3600w-116)

![S316](https://raw.githubusercontent.com/maditnerd/genericRadio/master/img/s316.jpg)

Importer
```json
[{"name":"A1","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1398083","radiocodeOff":"1:1398092","pulse":""},{"name":"A2","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1398035","radiocodeOff":"1:1398044","pulse":""},{"name":"A3","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1397843","radiocodeOff":"1:1397852","pulse":""},{"name":"A4","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:1397075","radiocodeOff":"1:1397084","pulse":""},{"name":"B1","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4543811","radiocodeOff":"1:4543820","pulse":""},{"name":"B2","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4543763","radiocodeOff":"1:4543772","pulse":""},{"name":"B3","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4543571","radiocodeOff":"1:4543580","pulse":""},{"name":"B4","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:4542803","radiocodeOff":"1:4542812","pulse":""},{"name":"C1","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5330243","radiocodeOff":"1:5330252","pulse":""},{"name":"C2","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5330195","radiocodeOff":"1:5330204","pulse":""},{"name":"C3","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5330003","radiocodeOff":"1:5330012","pulse":""},{"name":"C4","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5329235","radiocodeOff":"1:5329244","pulse":""},{"name":"D1","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5526851","radiocodeOff":"1:5526860","pulse":""},{"name":"D2","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5526803","radiocodeOff":"1:5526812","pulse":""},{"name":"D3","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5526611","radiocodeOff":"1:5526620","pulse":""},{"name":"D4","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5525843","radiocodeOff":"1:5525852","pulse":""},{"name":"E1","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5576003","radiocodeOff":"1:5576012","pulse":""},{"name":"E2","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5575955","radiocodeOff":"1:5575964","pulse":""},{"name":"E3","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5575763","radiocodeOff":"1:5575772","pulse":""},{"name":"E4","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5574995","radiocodeOff":"1:5575004","pulse":""},{"name":"F1","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5588291","radiocodeOff":"1:5588300","pulse":""},{"name":"F2","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5588243","radiocodeOff":"1:5588252","pulse":""},{"name":"F3","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5588051","radiocodeOff":"1:5588060","pulse":""},{"name":"F4","description":"SCS-S316","room":"1","offCommand":"","onCommand":"","icon":"fa fa-flash","radiocodeOn":"1:5587283","radiocodeOff":"1:5587292","pulse":""}]
```
## Pheonix YC-4000
[Détails / Liens](http://www.cdiscount.com/maison/bricolage-outillage/lot-de-4-prises-telecommandees-yc-4000s/f-117044105-yc4000s.html)

![y4000](https://raw.githubusercontent.com/maditnerd/genericRadio/master/img/y4000.jpg)

Importer   
A venir
