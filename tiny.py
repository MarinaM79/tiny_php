# -*- coding:utf-8 -*-

# Tiny_PHP 的 Python 命令行工具

import os
from sys import argv

try:
    action = argv[1]
    name = argv[2]
    if(action == 'Controller'): # 创建控制器
        os.chdir('app/Controller') # 切换工作目录
        try:
            has_dir = name.index('/')
        except Exception as e:
            has_dir = False
        if has_dir is True:  # 是否是包含目录的控制器
            dir_name = name[:name.index('/')]
            file_name = name[name.index('/'):]
            if os.path.exists(dir_name) is False:  # 文件夹不存在 , 创建文件夹
                os.mkdir(dir_name)
            print('app/Controller/'+dir_name+file_name+'.php')
            controller = open(dir_name+file_name+'.php', mode='w+')
            controller.writelines("<?php \n")
            controller.writelines("namespace app\\Controller\\"+dir_name+';\n')
            controller.writelines("use src\\tiny\\DB\\DB;\n")
            controller.writelines("use src\\tiny\\route\\Request;\n\n")
            controller.writelines("class "+file_name[1:]+'\n')
            controller.writelines("{\n\n}")
            controller.close()
        else:  # 不包含文件夹 , 直接创建控制器文件
            controller = open(name+'.php', mode='w+')
            file_name = name
            controller.writelines("<?php \n")
            controller.writelines("namespace app\\Controller;\n\n")
            controller.writelines("use src\\tiny\\DB\\DB;\n")
            controller.writelines("use src\\tiny\\route\\Request;\n\n")
            controller.writelines("class "+file_name+'\n')
            controller.writelines("{\n\n}")
            controller.close()
        print('Controller create Successfully !')  # 打印控制器创建成功提示
    else:  # 所有命令都不匹配 , 命令不存在 , 提示用户可执行的命令
        print('Command Not Found : \n')
        print('\nUsage :\n')
        print('\tController [Controller_Name]     create a controller\n')
except Exception as e:  # 命令行未给参数 , 打印帮助信息
    print('\nTiny_PHP : ')
    print('\nUsage :\n')
    print('\tController [Controller_Name]     create a controller\n')
