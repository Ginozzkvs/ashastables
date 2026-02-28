import socket
ports=[9100,9101,9102,9103,515,631,6101,4000,8000,80,443,139,445]
ip='192.168.0.137'
for p in ports:
    s=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
    s.settimeout(1)
    try:
        s.connect((ip,p))
        print(f"Port {p} open")
    except Exception as e:
        print(f"Port {p} closed ({e})")
    finally:
        s.close()
