<?php include '../functions/functions.php';
if (session_id() == '')
{
    session_start();
}
if(isset($_SESSION['EMAIL']))
{
    session_unset();
    session_destroy();
}
$signup = false;
if (!empty($_GET['sid'])) {
    $res =  DecodeThis($_GET['sid']);
    if(count($res) == 2)
    {
       $sdate = intval($res[1]);
        if (!empty($sdate) && is_int($sdate) && $sdate < time()) {
            $sid = $res[0];
            if (!empty($sid) && $sid[0] === 'E') {
            $signup = true;
            }
        } 
    } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4b92d0">
<title>PTR - Start</title>
<link rel="SHORTCUT ICON" href="../images/favico.ico">
<link rel="icon" href="../images/favicon.png" type="image/png">
<link rel="stylesheet" href="../stylesheets/normalize.css">
<link rel="stylesheet" href="start.css">
<script type= "application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
</head>
<body>
<form class="form preload" method="POST" action="#">
    <div class="loginLogo"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="548px" height="219px" viewBox="0 0 548 219" enable-background="new 0 0 548 219" xml:space="preserve">  <image id="image0" width="200" height="80" x="0" y="0"
    xlink:href="data:image/png;base64,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
pZiplFopZmqlVoqZWqmVYqZSaqWYqZVaKWYqpVaKmVqplWKmVmqlmKmUWilmaqVWiplKqZViplZq
pZiplVopZiqlVoqZWqmVYqZSaqWYqZVaKWZqpVaKmUqplWKmVmqlmKmVWilmKqVWiplaqZViplJq
pZiplVopZmqlVoqZSqmVYqZWaqWYqZRaKWZqpVaKmVqplWKmUmqlmKmVWilmKqVWiplT1+zPvTr5
lQT57Q0KIf1UK/0Pjis8bdgM6vMAACBGSURBVHja7Z15YBRF9sc/SSBcAUIkciseKKJoVpAVRQ5B
ZUEuDSD4w3td8GBVUHFBXBBFwQPRxftcLlEBERUFVzF4ICACgqIgyCGHHOEmZ/3+mEkyV/f0TLqn
ZtLvk38y3dWv3uvp+XZVddXrJIUgCEL5qBTxESMYr9nnXBRwiAJyKeAA+9nPfvaxmz/YyU72avYv
FDm00+1C3HEJS6I6rh05ul0v5TAFwFHyOEoeBzjIAQ6Sy5/sYQd72EaubhdjQ+RCop90AOoY7j/K
JjaxmY2sYx3bdLsrVGDSALNrEfazmc38xmY2s4mNHNftsjMkopCEozpnc3bppwOsZQXLWcHPFOl2
TXAddajDX0o/FbGR1axmJUv5U7drdlIRhcSf2lzERQAcZAk5LGYZhbqdElxKCmdwBtkA/MZSvuR/
/KLbKTuo+EJSRi260Q04wEI+ZAE7dTskuJpTOZUBwHbv9XhYt0PlIVm3AxqoTTavs53PGUKmbmcE
19OIG3iHPXzMDdTW7Uy0uFFISiLvyBR2MJ9epOh2RnA9VejK6+xgFlck4q8yAV22lRS6M5ctjKGu
blcEgWr0ZQHrudf0SVAc4nYh8dCQ0fzOFE7V7YggAKczgc08Rj3djlhHhKSE6gzhJ56ngW5HBAGo
xf1sYpx31lTcI0LiSyqD2cg47zQjQdBLNUbyK4MT4VeaAC7GmGqM5Cd66XZDEACoy/N8SQvdboRD
hCQUjZnLTBl+FeKEi/meu0jS7YYZIiRG9GcVl+p2QhAAqMLTzOME3W4YI0JiTEM+5f74vg8ILuJK
lsZvF0eExIwUHmMW1XW7IQgAnMaSeE1HIUISjmwWkaHbCUEAoA4L4rPDLUISnrbk0Ei3E4IAQA3m
x6OUiJBYoQULZXmfECdUYzYtdTsRiAiJNc7iM3kcLMQJtfmIxrqd8EeExCotmUWqbicEAfDMdKqq
2wlfREis04nXdLsgCF5a8bRuF3wRIYmEaxmq2wVB8PIPuup2oQwRksiYSGvdLggCAEm8HD9ZS0RI
IiOVmbI2WIgTGvOIbhdKECGJlNN4VLcLguDlVrJ0u+BBhCRybudi3S4IAgApPKXbBQ8iJJGTzBRJ
Fy3ECZ3oqNsFECGJjnO5SbcLguBljG4HQIQkWsbKkKsQJ7TnQt0uiJBES31u0+2CIHi5XbcDIiTR
cw/VdLsgCAD00//iChGSaKnHLbpdEAQAUhmg2wXdLxH/ggWWy6ZQC0ginSQyyKAOGTSgsjbfb+c5
lMWyyznusDfpts65zWW5w/5CruM1WGEzL0R9bG2SgcqkUZ3a1Cad+pyoJYr/Y5KWekvRLSTf8ni5
jk+hISdzCi3IIov6MfX9TDqzyGLZux33ph05Nlr7kcsc9zg+2FbOKzCQKjSiCc1ozrm0idlLwVtx
JutjVFdIdAtJeSliK1tZ4v3UiEvpQueY5TMbbFlIBLeQx2/8xmIAkmlJe66gC1Ucr/dKvUJSscZI
tvNfrqcxbXmO3TGorzu1dIcsxDHFrOJZruREBvGVw3VpfqVbxRKSEr7lThpzLasdrqcqV+kOVUgA
DjKVdvyVDx2s4yK9bwmumEICUMB0sujJr47Wco3uMIWE4TuupCe/O2Q9Re8KsIorJACKD2jJQw4+
MekoM1yFCPiANqUjenZzic7AKraQAOQxlrZscMh6FbroDlBIKHbTmXmOWG6vM6yKLyQAP3Ah3zlk
u5vu4IQEI58BjlyN5+pck+4OIYG9XO6QlHTUHZqQcBzlGg7ZbrUGZ+gLyS1CAgfoxR8O2G0W42lw
QkVgkyOL/7P0BeQeIYGd3GB5SnskaB3kEhKUZ9liu83m+sJxk5DAQqY4YLWN7rCEBCTfgSSJzfSF
4y4hgX+xx3ab5+kOSkhI3iLPZoun6QvGbUJykIm228zSHZSQkOyPYOW7NZrqC8ZtQgIvcdRmi5k0
0B2UkJDYPWU+U19SDfcJSS4f2G5T4yCXkMB8YbO9JH2Z0twnJDDddosan98LCcyv7LPZora2sRuF
5GMO22yxqe6QhATlR5vtpesKxI1CUsCXNls8SXdIQoJidzIibflx3CgksNRmezK3VYiObTbbEyGJ
KStttidPbYTosHvRRlVdgbhTSH622Z72t4oICYrdUxG0Zcdxp5DYnaUqQ3dAQoJi97C/tkQC7hSS
fNtTQ8fqtQNCxaJAtwN24U4hgf022xMhEaLB6demxQy3CskBm+3JaymEaKiu2wG7cKuQ5NtsL0l3
QEJCkmqzvYO6AnGrkNiN5JIXosHulqwTibss4VYhsTtujWl3hQSmrs32jugKxK1CUlO3A4IAtr+l
2u55KZZxq5BIC0KIB0612d5eXYG4VUjSdTsgCNifycbu+VGWcauQnGCzPW2j5UICU53TbbZof0Zi
i7hTSOpSxWaL2kbLhQTmAtu72NK1iSn25w+x/71pQsWnq832dlKoKxR3CslZtlu0e6as4Aa622xv
o75Q3Ckk59huUcZIhEhpRkubLYqQxBi7v8BDFOkOSUg4brXd4gZ9wbhRSJK5yGaLO3SHJCQc1bjR
dpvSIokpWdSx2aLdCfOEis/dtk9BgNX6wnGjkHS23aK0SITIqM8I220etz2FaAS4UUiybbe4RXdI
QkKRxPMOrPZare/hrxuF5Aza2G5TY99USEDupLcDVpfoDMl9QnK9AzZ/0R2UkED0YqIjdu1+7VtE
uE1I0hjigNVfdYclJAw9mWV7XjSAAttfSR4RbhOSIbY/sYE98tRGsEQyDzLbERmBb/XOrq6ks/KY
U5f7HbD6ve6whITgL0yivWPWF+gNzl0tkgkOPLuHH3SHJcQ9LZjGcgdlBD7UG6CbWiR/4wZH7H6n
OzAhjsnkSq6jg8PvGVjLKr1hukdITmWqI1+mYrHu0IQ4JIPzaU9XWsWk1T9Vd7huEZJ05jr0ht51
+rJSCXFEKnU4gaY0pSmnk8UpMay7mOm6w3eHkJzIh7av+C1B2iOJzTksjPLIJG/m39qkkqH1zUZf
6J9b7QYhacdbDt4f5ukOTygX6XTR7UK5mazbgYr/1KY6E1nsoIwc1jsNSBDYwAe6XajYLZIaDOZe
6jlaxyfk6Q5TcDkTKNbtQsUVkgsYyLVkOl7PNN2BCi5nA2/qdgEqnpDUpT3t+RtnxKS2fbqnAQmu
ZwT5ul2AiiAkadTnRE6nOc05m2YOT/zxZ2Z8fImCa/mK2bpd8KBbSK6hdRRHeR68VSONTKpp813x
H211CwIUcU+8vJpNt5A0panuUxA1C1mn2wXB1UyKn+UZFf3xr5M8p9sBwdX8xCjdLpQhQhItPzBf
twuCiyniBo7rdqIMEZJoeSheeqeCK3kgfro1IEISLTkyNV7QyGye0O2CPyIk0VDEUN0uCC5mKYPi
rT0sQhINL0lWNEEba+jBUd1OBCJCEjlbeEC3C4Jr+Zku/KnbiWBESCJFcZPefN2Ci/mFLuzW7UQo
REgiZRKf6XZBcCnLaMd23U6ERoQkMpY48PJnQbDCe3SKx06NBxGSSNhGP1mmJ2igiAfpyxHdbhij
e61NInGAbuzQ7YTgQrZyPZ/rdsIcaZFYJZ+rWKPbCcF1KN7i3HiXEWmRWOU4V/M/3U4IrmMNd/Cl
biesIC0SKxylJx/pdkJwGfu5h/MTQ0akRWKF3fTmG91OCK5iH5N4hoO63bCOCEk41tKDTbqdEFzE
HzzP5EQSERAhCcd0hiTaVyokLIrPeIF5FOh2JHJESIw5yt28pNsJwUWMZ6RuF6JFBluNWMx5IiNC
TLmbFrpdiBYRklDsYzCd2KDbDcFlVGMaqbqdiA4RkkAKmcKZvBhviWMEV5DFON0uRIcIiS/FzKAl
t7NHtyOCaxlGJ90uRIMISQkFTKMlA/lZtyOCq0nmTerodiIatwXYy2Ocwv/JC6+EOKBJIr4xye1C
UswiBtKIB+I1YYzgMEtIiuKvJhsd9GkgA3Wflkhxr5AovmYYTbmMGeTpdkZIMA4ziCIH7f+Hk3WH
GBluFJKjfMRtnMTFPMVW3c4ICco3POag9XTeJEV3iJHgppmtR/iGHL7ka8lyJtjAWLrSyjHrHbjX
UamymYovJMdYyypW8B2rKNTtjFCByOc6llPNMftjWMgK3UFapSIKST6/s4nNbGAdP7GZYt0OCRWU
dTzAJMespzKVVvH3KqzQJKKQHKYAOM4xDlNALrnsJ5f97GA329nFLpEOIUZM5kq6OGa9OU9wm+4Q
raFbSB6Tt9YJCYziRlY7OIFsMB8xX3eQVnDjUxtBsI9tjrYZkniFerpDtIIIiSCUj5nMdNB6PV4h
SXeI4REhEYTychvbHLR+JYN1BxgeERJBKC/7udHRtBNP0Fx3iOEQIRGE8rOIyQ5ar87UeE94JEIi
CHbwgKNrx1sxRneA5oiQCIIdHOM6R5de3EsH3SGaIUIiCPawgrEOWk/hTdJ1h2iMCIkg2MVjjr6R
8WT+oztAY0RIBMEuihjEYQftx3HCIxESQbCPjdzjqP24TXgkQiIIdvKyo2tj4jbhkQiJINjLLfzp
oPUODNcdYChESATBXnZxq6P2x5KlO8RgREgEwW7m8pqD1lOZ5mBetigRIREE+7mLTQ5ab8EE3QEG
IkIiCPZziOscfV3F7fxNd4j+iJAIghMs4QkHrSfxGpm6Q/RFhEQQnGE0PzhovT6v6A7QFxESQXCG
fAZx3EH7PR1+OhQRIiSC4BQ/MtJR+09xhu4QSxAhEQTneJrPHbReg6lU1h2iBxESQXAOxQ3kOmj/
Ah7SHaIHERJBcJIt3Omo/RG00x0iiJAIgtNM5R0HrafwX2rpDlGERBCcZzB/OGi9Kc/pDlCERBCc
Zx83Ofq6ikH01x2iCIkgOM8nDqdJfJ4megMUIRGEWHA/6x20Xoc39f6WRUgEIRYcZRAFDtrvxDCd
4YmQCEJsWMY4R+2P05nwSIREEGLFoyx10LrWhEciJIIQKwq5jiMO2teY8EiERBBixy8Op27WlvBI
hEQQYsmLfOygdW0Jj0RIBCGWKG5ir4P26/OijrBESAQhtuzkH47a78PNsQ8qSeXEx+pBQdDCEi7R
UOsbXK87cB8uYUl5TUiLRBBiz1B+1+2CvYiQCELsOcj1FOt2wk5ESARBB4t5SrcLdiJCIgh6GMVq
3S7YhwiJIOghj0Hk6XbCLkRIBEEXq3lQtwt2IUIiCPp4ksW6XbAHERJB0EcxN3BQtxN2IEIiCDrZ
zFDdLthBJZY7+n5SQYhvftTtAG/yF87W6kFu+U0kOZncWhAEdyBdG0EQyo0IiSAI5UaERBCEciNC
IghCuREhEQSh3IiQCIJQbkRIBEEoNyIkgiCUGxESQRDKjQiJIAjlRoREEIRyI0IiCEK5ESERBKHc
iJAIglBuKul2IG5JJwmAXMJlWigpud+kTE0qAYcpMCxRlWoAHKTIpJ7wFvI4aim+TNpxKtU5wDZy
+NOwXHWqmNo5zrHS/yuTBkA+R8LUnkqNkCUrUdP0OOWXO8Nz5gs4bFC6CtUp8slAVoXqFs5MMQcs
ncFU/kpLMihmNz+wMuh783wfZt8Y1CA1ICYP59CKBqSSy48sDXE2S645c3zrDneE73VXg9Sg40OT
RmVvSSV/of9ylYd2YcqdrkowLpOidiullBppUmaE18oDph51N7EwVSml1BILsZ2v5qlCVUaReled
Z1B2ijJnvE/Z7t5t68N6MM5bclrA9nZhassN8R3lqroGtQxRSq0O+Bye3y2cwarqIbXT76jtaqiq
5FemucpXSr1sYiVNbVdKTQ3YOkCt8bN8VD0bFGGupUi6R3BES5+y07zbxoQ9DxtKapKujRl5wIAw
Zfp7y5nRkUz2AldZqHMEJ0blaxV6cJRjtKWRabkkxvAdPUhhNW8yiddZQRJXs5x7bDlnRRRyBq3C
+DDAwlmzSm0essmSVRqyjH9Tj61M50meZA65NOQZFpPhU+pnJgE3kWVoZyQNOchwny2pzGI653CI
93mGx3mLzVTjDtZwfoxjzAOuCVOmDaeRj6fFbtsdvKL95SqlBiuldgXcZwL/1iqlbgvTInleKTVA
7VNKnWZYZoRSappaqJR63sQj4xZJT6XUG2qmUmqoiSdJ6hWllFKL/e5AZ6i5Siml7gtxxBSlVI7F
c9ZdKbVKvaqUmmBaro1SapmaYtgiOSmC7+hOVazy1Zkh9we2SIL/lFJqSIRXRlW1Vim1X12rUkq3
1VDjVLFS6jNV2adkmtqmlFpsYKeZOh70bb2mlCpSj6iapVuS1QC1Xym1WzUx8Wl82EhzI4h0mlJq
tNqvlGplWm6SUuoez3UpLRIzvmE7J3KpSYlzacGvYd6YlkIfiviERUC2ackajEJxCy2i8DUb+ISP
w9RxHzcDT3Epa3y2/kIfXgAepWO5z9lB3gGuMe2RDwRmhxkNscpK3qUyE2yxZY1htKCArkzzGVU4
wijuAi71S+V8mPuA9vQNaWcCVfiBKT5bLuFGYBgjOVS6rZgZdOEYmbwewxghn/cxb5Mk0xeYXfJB
MKaY9zDv3AwA3gtjpT31+JZ9fEg4IanEUj6gUhQ/iyr0pIhPWIDiYhoYlDqXh4H/MixoYFBxJ8tI
YUq5r4jqfMZ+mnCxYYlk+gPv2nbtjaaInjZIoFWuB95iadD2ySwERnkHnD3MIAeYQNWg0pfTG8Ud
FPpsuw7YxLNBZVcwBugcwxgB3gX6m3xLHWnIcjaTAiIk4XgX6G343CLJkpB42grwCcW05pQwpUdR
THc6R+jnZdTmO/axixUkc7VBqceozC6D1x8U8gBwFj3KecYqU8BczOS3I/VZza/lrKeMn5kKPBGj
azmdZsD/Qu4bB+Ryjs8WxVCKaOo3DgJQiWeAN/nKb2sW8GXIp3aTORBg2XkWcoAmXGS4fwCe9kga
iJCE4yt2kE43g70XcjKbWWFqIZmrgLnATr4lXJsE1jCTyH8W2cA88NYUuo4z6Qo8Zfjygc/YyE7q
2XDW3gWyDacWeDo2djKGfFpxra02jfA8QD4Wcl8OWZzCt37bfuAl4P6AIfDbaE4u9wUcXwUMHmUf
42JO5LmYRFhCHvMwviGkchU+N1EREjNSvJ2b/gb7BwJzMJ9ncgn12eQdkZgDBv1lX/5NIVlcH4Gf
qfTCIyGeOtqFlINrSaKY/5rYaU8jXrLhvC0i13BsyXMBvmtDLWVs4hXgEe9MHGfZTzEYtCsVq0Js
fZC9pPGoz5ZMxgD/Cpq9kwucalDv2rBzOuzH7IbQlQzW8EvJRxESM9KAd4Aefv3eElLo691vRjbw
gff/uUBrmoY54lfeAB62NHnKQxfS+ZWfAVjHelLoE6LU5cDX7DCx8wfFtpw3z0Bd6HtZV+qwnrW2
1ANQC4BxHKOJTQ+wzTnGj0B/SxPCPOzlQWAQbUq3jCOdFSEkexnQiYYxiMIan3DQ8IbQH79OvQhJ
OJawg+r0DLHnUuqxPcSgmy+e8Yq53k8bWEOS4QgG3lmCMJZjNOJeyz5mA++XfppDqM5NJc6DMN0w
+5gF9Ak5tjSA8OIbCZ4f9A4mA/fZ0jELx6tAGx6O4LfzEj+QxDNeX7O4mWJuDzEW8gbFVGWaVxz1
Y9y5qUEvREgiophZhH4MNhB4O8w9/GIasJcvSz+bd25K2iBbeQoYbvj0xZ/K9PZaLqujI5kBpZpQ
ldi9oHIhe6kdYmypBj2AGQ7U+Ch/UosxMYjteZYAI1lCD4tLTIoYiuJCBgJJTCaFV0PegNYwHujI
j9xG7RhEEp6ZhL4h9KQGa3yvJhGS8EwHrvCbswhQhd7efWZ4OjZl9545QBtOClvnBHaRxsOW/OtM
He9ArodlbAvRuakPwK6ozkAWy0P8nW5yRAHvEUp+e1GDH1gXpr75IWq7I8wxBxkD3ByDt+gW0JUZ
QFvmsYOpXBsk2sHkMAN4nOoM4BL28oBBuVHczzGa8B928CF30cxWz0eEOK8zTY/4lD0hbwjXEHjt
Rzirzz1/uapsnc1GpdTNAfv7KKV+8v7fzmBma7LappTq5bdto1LqrhBlRwTMIP2HUqrQb/6p0czW
V5VSL/ptmayUWhhQqqtSSqkOEZ8H47U2LYPKdveZX9lBKXVEpQWUmKeUutf7/7SI1tqMD+Gb/xmp
pH5SSn3ks9+Zma0l8X2oiry+Faqv1TB1gmn5RuqQUmqS2q6U+rtpydPVC+pwady/qIkG83Z9/6zN
bA1FqKOmKaVGeP9/Xin1dsD+DJWnitXJPuewu6z+tcIMRjKQV/22DQCmhjmuLY04wqd+2+YwjGwm
ha3zVYbSgie4Iky5yvTCt2PjqeNOOlKXPT7bUgCC+uVjaR6wZQ+3BdXxI3eHqHmTqV85bKMxPf3u
WxlcQXGYeyDAtewO2rY57FGF3M/7/I0uLApbtvwsZjGN6cGVdKIabWnLOF7iIcOH69t5hPH8E1ga
cCUFsoHB3McV9KArmTRjOMP4mHtYX26fJ5cO+5dxOMwxMxnMldT0mWsLfUglh9/9ykWlxm74822R
tFBKFar6PntrqiOqWJ3q/WTUInlaKfVewLaLlFJFqlFQ2cAWCaqbUkqprn4eBbdILldK5aoqfttS
1J9KqVv8tnVQSil1acDROUF3qMC1r5GutSm7wz2plPrAb//flVJflH4ybpFEstbG/4z8Tym1SiV7
PznZIin7q666qde9bYht6hzDclXUeqVUYZj1K75/yeoCNV5tUUopdVwNNClp/1qbEaU+bFEqoO7P
lFK3+p1DaZFYYh2rOZd+TC7d0ovqfMNvpkclkQ0UcKvf1mTySeVqH1tGfMQiujCRhYYZSsAzdLst
aNbJVurSl1d8tnhaJ4Eri0dT1+dTO4NZr9ExnXu4nAz2lW4J7lnbzXCWcS438JqjtfhzlI/4iOGM
5g4asZBz2BuyXB6zGcH2CJ6cFbOMZYzmeiaSzlT+4IsYxlXiw9sMZ6DP99aAjuQHzgQSIbHGDM6l
v8+P38pP4kIaA/1DTmfLtiAkMJzvOYfrvT+LUM+HKtEbOJsXQ+zrxAk+l/TvKJKClgN+7vcpAztZ
wXrO5KpSOWtAR/JsnooWyPdM5TrGMoNjYDFBkT3s5Z9sYDL1+SejbbVcwCusZAlVecRkBZNzzGQ4
l1GnNHFXP5JZ4HN7AERIrDKDR2lLE7YCkMFlFDIrzDF9gY0hfjgnMYCLaWA6NczDKt7kRkYzjTzg
EHWCSnSkLsU8GUJk7qEyvXzuzIf5jdO4wLQ+80wmkTOTh3zaRX1J5qPAC9B2RpFNI27jSYfrCcVz
DOEsetssJAAreJXbaevXvosVnhtC79LVx/0JcRMVIbHG73xFO7J5GvAMNi0IMSDoi2fi2cs8HrQn
jT5U5WpLKydG0Z+T+bth2b5ATtCaDYDzuYxsvyb+Yk7jr1Q2mWqdZfNZm8FDXFo66NsPpzs2AFt5
mpGM4GWfFIuxQrGQs0wfi0fPp9xOEs35OuZRwXTG0M8rJCdzIYeCB21lHolV3qZszU1ITQ7AM1sk
VEP+MJ8SfvGehz+YCIyiGoQQAM9skdAL4N4DOpMesKUO3Q3rqkIHm8/ZelZQyTuj5SQu4iDzba4h
FI+zi7r8Eyxmr42MNG4w6WDshBApA6yRTDeuM9zrmQFkfdmEncwEOnMCAH1JYm7wmRUhscosCmnD
yUAmnTgS8MA1mL7ASjaG3DcHz2I+KzzBDuoxhFA/i45kepcVBvM+xd7FfCV8yu/ASO+D4GD6ODA1
ewaelojnApzNcdtrCOYQo4G7qW2wRrc8pLKR100mx9UBk0Ta5szgQx4z7COkA9FOKCwvv7DCO83A
8CYqQmKV3SzyPofJphLvh3n+7unYGGUqmUeBN71AeA7zIHAv1UI01bPxZHELxU6+xr/dU8gYoDXD
QpavzIN+SXbsYRbFdCITa604u3iNtdThzrC57CMnn/lAtmH3pQOwMkrbc4EG3GhiOY8Ntkdkjel4
vsHTaM2uUPN0REisU9K5sdLXb0NTjBen7WMxVjs38Aarqc8/gu6vno6NcVql2XgSHvla+gJ4mN4h
So+nhd/jYnvYSg4pXMWpXMBOg3RA9lPIvcDdEazQtc44jlKJ10N2MvrQBkwTNZjxNiuBCSGnxTdm
MPCOA20sq74V04m69AdmhrrhiJBYZzbHac1FtGdPwGzVYPoCq8uyNQQxF2hvMV98EcOB+2kcsL09
9VAmQjIHTwrGMhT92EQqs/i3X+6OmrzMMDYxwrbM7mXMAPrSD3jbdD6MvXzMIjIsrlWKjE0MAdrx
CSf7bU/iOt4CFkfd7ipmEAdIZ3FQfrzWfE4dchnl/IkzYDs5VKa3cbtSntpY5yDzyWYBybwTJsmM
eccGYA6TSaG3xTRCC1lA16AxlWzgO7YYHrWZFbQi2+8O+ScdmU9LHuJ25vI9udTnHHqTwR9044DB
S75aG4z1AKw3zB/n4T2epRMXEn5BQRk5Jp2sbhanig9jpe2Dxx7eohrP0Y71vM+XbKWQdM6jB2cB
39OXcC9UM2Yt3ZhLAxbxNR/yM4eoQzM605Ek9tIzYFJ65IwNSvlYxkReMD12Oh14klps4LtQu0VI
ImEm2dQkfMfGk7zIbOrVHyylLX0t5yMbzmUBg6SeMRbzzB5zaMUV1PIbXdnChfyLO6nLLaXbFLMY
yi5gv/cNeP5UNczbRdhxiD18SndqsJ7lFmPFdHV0qkUbq3mDmyzXGBkv8j2P05F+3oFkDweYyETy
y2X5a87jUa7lIr9cqXnM5AEL847CUddvHrM/6WGOfZfnqIXhtZ8UvXxWcLJIYX3AkGoqLQH4PuCu
k8aZ+CYNyuQkisMMup1EJoU+qfnq04jDJnfbFlQDNpTO1vT48ovfYqpAanM68HOIH3sa3WjDSaSx
j1XMLU3F3IZUlgT5acYxn6QAtTnd77OHBjQE9gYtuzuFDPYFLP3znEkz1vmME2SR4nNGAqlFswD/
gmkFbInyOUszLqMlmaRygE0s47Owj5sbUZ98vxeBhCaTy2hNQ2pyiO2s5JOwHjaifphIswyf1nnY
zs7S/08hw++zh7OpCiHOdytgw/8DpnNnYQpeWlAAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTctMDIt
MDNUMjA6NTE6MjYtMDc6MDBXXDm+AAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE3LTAyLTAzVDIwOjUx
OjI3LTA3OjAwgHaKtgAAACl0RVh0ZGM6Zm9ybWF0AGFwcGxpY2F0aW9uL3ZuZC5hZG9iZS5waG90
b3Nob3DkrZ9UAAAAFXRFWHRwaG90b3Nob3A6Q29sb3JNb2RlADNWArNAAAAAE3RFWHR0aWZmOlhS
ZXNvbHV0aW9uADcyDlBxhQAAABN0RVh0dGlmZjpZUmVzb2x1dGlvbgA3MpNfkPMAAAAodEVYdHht
cDpDcmVhdGVEYXRlADIwMTYtMTEtMDdUMTc6MDA6MTUrMTA6MDDNQ23WAAAALXRFWHR4bXA6Q3Jl
YXRvclRvb2wAQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cyl97wykAAAAKnRFWHR4bXA6TWV0
YWRhdGFEYXRlADIwMTctMDItMDRUMTM6NTA6MzErMTA6MDC6TIo2AAAAKHRFWHR4bXA6TW9kaWZ5
RGF0ZQAyMDE3LTAyLTA0VDEzOjUwOjMxKzEwOjAwhujZiAAAADl0RVh0eG1wTU06RG9jdW1lbnRJ
RAB4bXAuZGlkOkNGM0U3ODdEQUZBNEU2MTFCN0EyRDc5NjU5NjU1Njk2LwXecwAAADl0RVh0eG1w
TU06SW5zdGFuY2VJRAB4bXAuaWlkOjhBRkEyMTEzOERFQUU2MTFBNjRDODBDM0RFRkY1MUM5vGM/
nQAAAEF0RVh0eG1wTU06T3JpZ2luYWxEb2N1bWVudElEAHhtcC5kaWQ6NTA3RjczOTFCN0E0RTYx
MUI3QTJENzk2NTk2NTU2OTba/CzoAAAAAElFTkSuQmCC" />
</svg></div>
    <div class="OutsideContainer"></div>
</form>

<script>
    <?php 
if ($signup) {
        echo '$(".OutsideContainer").eq(0).load("signup.php", function(){
        document.getElementsByTagName("form")[0].classList.remove("preload");
        $("#empID").val("'.$sid.'")
        $("form").addClass("signup");
        });';
} else {
    echo '$(".OutsideContainer").eq(0).load("signin.php", function(){
    document.getElementsByTagName("form")[0].classList.remove("preload");
    $("form").addClass("signin");
    });';}?>
    function SignInSignUp(page, formLayout)
        {
            $(".OutsideContainer").addClass("HideTransition");
            setTimeout(function(){
                if (formLayout)
                $(".OutsideContainer").eq(0).load(page, function(){document.getElementsByTagName("form")[0].classList.add("signup")});
                else
                $(".OutsideContainer").eq(0).load(page, function(){document.getElementsByTagName("form")[0].classList.remove("signup")});
            }, 500);
           
            setTimeout(function(){
                $(".OutsideContainer").removeClass("HideTransition");
            }, 700);
            
        }
</script>
<script src="../javascript/login.js"></script>
</body>
</html>