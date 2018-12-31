#-*- coding:utf-8 -*-
import os
from sys import argv
import re

try:
  action = argv[1]
  name = argv[2]
  if(action == 'Controller'):
      os.chdir('app/Controller')
      try:
        has_dir = name.index('/')
      except Exception as e:
        has_dir = False  
      if(has_dir!=False):
        dir_name = name[:name.index('/')]
        file_name = name[name.index('/'):]
        if(os.path.exists(dir_name)==False):
           os.mkdir(dir_name)
        print('app/Controller/'+dir_name+file_name+'.php')
        controller = open(dir_name+file_name+'.php',mode = 'w+')
        controller.writelines("<?php \n")
        controller.writelines("namespace app\\Controller\\"+dir_name+';\n\n')
        controller.writelines("use src\\tiny\\DB\\DB;\n")
        controller.writelines("use src\\tiny\\route\\Request;\n\n")
        controller.writelines("class "+file_name[1:]+'\n')
        controller.writelines("{\n\n}")
        controller.close()
      else:
        controller = open(name+'.php',mode = 'w+')
        file_name = name  
        controller.writelines("<?php \n")
        controller.writelines("namespace app\\Controller;\n\n")
        controller.writelines("use src\\tiny\\DB\\DB;\n")
        controller.writelines("use src\\tiny\\route\\Request;\n\n")
        controller.writelines("class "+file_name+'\n')
        controller.writelines("{\n\n}")
        controller.close()
  print('Controller create Successfully !')    
except Exception as e:
  print('Tiny_PHP : Command Not Found : \n\nUsage :\n \tController [Controller_Name]     create a controller\n')
