#!/bin/bash

/usr/bin/find ./ -iname "*~" -exec echo {} \;
/usr/bin/find ./ -iname "*~" -exec rm {} \;

/usr/bin/find ./ -iname "caderno_chamada_*.ps" -exec echo {} \;
/usr/bin/find ./ -iname "caderno_chamada_*.ps" -exec rm {} \;

