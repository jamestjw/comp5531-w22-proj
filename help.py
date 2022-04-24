USER_MAP = dict()
USER_MAP['drouba'] = 'andi'
USER_MAP['James Tan'] = 'james'
USER_MAP['James Tan Juan Whei'] = 'james'
USER_MAP['chris7neves'] = 'chris'
USER_MAP['Zachary-Concordia'] = 'zack'
USER_MAP['Zachary_Concordia'] = 'zack'
USER_MAP['ZackDaw'] = 'zack'

ID_MAP = dict()
ID_MAP['andi']=29605991
ID_MAP['zack']=40203969
ID_MAP['chris']=27521979
ID_MAP['james']=40161156

FULL_NAME_MAP = dict()
FULL_NAME_MAP['andi']='Andréanne Chartrand-Beaudry'
FULL_NAME_MAP['zack']='Zachary Jones'
FULL_NAME_MAP['chris']='Christopher Almeida Neves'
FULL_NAME_MAP['james']='James Juan Whei Tan'

import sys

# use stdin if it's full                                                        
if not sys.stdin.isatty():
    input_stream = sys.stdin

users = set()

for line in input_stream:
    users.add(USER_MAP[line.strip()])

for user in users:
    print(f"{FULL_NAME_MAP[user]} - {ID_MAP[user]}")
