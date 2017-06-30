import os
import threading
from requests import get
import psutil

def getPublicIP():
    # Return Public IP
    return get('https://api.ipify.org').text

def getPrivateIP():
    # Return Private IP
    return os.popen('hostname -I').read().replace('"','')

def getMac():
    # Return Mac address over eth0
    return open('/sys/class/net/eth0/address').read().replace('\n','')

def getOsImage():
    # Return OS Specifications
    os = open('/etc/os-release').read()
    imageName = os.split('\n')
    return imageName[0].replace('PRETTY_NAME=','').replace('"','')

def getCPUtemperature():
    # Return CPU temperature in celsius
    res = os.popen('vcgencmd measure_temp').readline()
    return res.replace("temp=","").replace("'C\n","")

def getCPUusage():
    # Return % of CPU used by user as a character string
    return str(os.popen("top -n1 | awk '/Cpu\(s\):/ {print $2}'").readline().strip(\
    ))

def getRAMinfo():
    # Return RAM information (unit=kb) in a list
    # Index[0: total RAM, 1: used RAM, 2: free RAM]
    p = os.popen('free')
    i = 0
    while 1:
        i = i + 1
        line = p.readline()
        if i==2:
            return line.split()[1:4]

def getDiskSpace():
    # Return information about disk space as a list (unit included)
    # Index[0: total disk space, 1: used disk space, 2: remaining disk space, 3: percen$
    p = os.popen("df -h /")
    i = 0
    while 1:
        i = i +1
        line = p.readline()
        if i==2:
            return line.split()[1:5]

def StaticSpecs():
    PublicIP = getPublicIP()
    MAC = getMac()
    OS = getOsImage()
    RAM = psutil.phymem_usage()
    RAM_total = RAM.total / 2**20       # MiB.
    DISK = psutil.disk_usage('/')
    DISK_total = disk.total / 2**30     # GiB.
    # RAM_stats = getRAMinfo()
    # RAM_total = round(int(RAM_stats[0]) / 1000,1)
    # DISK_stats = getDiskSpace()
    # DISK_total = DISK_stats[0]

    return (PublicIP + ':_:' + MAC + ':_:' + OS +':_:' + str(RAM_total) + ':_:' + str(DISK_total))


def CurrentSpecs():
    PrivateIP = getPrivateIP()
    CPU_temp = getCPUtemperature()
    CPU_usage = getCPUusage()
    RAM = psutil.phymem_usage()
    RAM_usage = RAM.percent
    DISK = psutil.disk_usage('/')
    DISK_usage = disk.percent
    # RAM_stats = getRAMinfo()
    # RAM_total = round(int(RAM_stats[0]) / 1000,1)
    # RAM_used = round(int(RAM_stats[1]) / 1000,1)
    # RAM_free = round(int(RAM_stats[2]) / 1000,1)
    # RAM_perc = ((RAM_total - RAM_free)/RAM_total)*100
    # DISK_stats = getDiskSpace()
    # DISK_free = DISK_stats[1]
    # DISK_used = DISK_stats[2]
    # DISK_perc = DISK_stats[3]
    return(PrivateIP +':_:'+ CPU_temp +':_:'+ CPU_usage +':_:'+ DISK_usage +':_:'+ RAM_usage)