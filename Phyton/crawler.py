import urllib,requests,json,ssl,http

class data_kpu :

    err_msg = ''
     
    def __init__(self):
        self.err_msg=''

    def get_json(self,url):
        ctx = ssl.create_default_context()
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE
        data = {}
        try :
          r = urllib.request.urlopen(url, context=ctx)          
        except urllib.error.URLError as e :  
           self.err_msg += "URLError"
        except urllib.error.HTTPError as e :  
           self.err_msg += e.reason
        except http.client.HTTPException as e:
           self.err_msg += "HTTPException"
        except ConnectionResetError:
           self.err_msg += "ConnectionResetError"
        except IOError:
           self.err_msg += "IOError"
        else:
           data=json.loads(r.read())
        return data

    def get_data(self,args=[]):
        i=1
        if len(args)==0 :
           url_wilayah = "https://pemilu2019.kpu.go.id/static/json/wilayah/0.json"
           url_jml_suara = "https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json"
        else:
           url_wilayah = "https://pemilu2019.kpu.go.id/static/json/wilayah/"
           url_jml_suara = "https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/"
           jml = len(args)
           for kd in args:               
               url_wilayah += kd + ('/' if (i<jml) else '.json')
               url_jml_suara += kd + ('/' if (i<jml) else '.json')
               i+=1    
        json_wilayah={}
        if i<6 : 
          json_wilayah = self.get_json(url_wilayah)
        json_suara = self.get_json(url_jml_suara)        
        data={"data_kpu" : {"json_wilayah":json_wilayah,"json_suara":json_suara}}
        return data

class data_kawal :

    err_msg = ''
     
    def __init__(self):
        self.err_msg=''

    def get_json(self,url):
        ctx = ssl.create_default_context()
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE
        data={} 
        try :
          r = urllib.request.urlopen(url, context=ctx)          
        except urllib.error.URLError as e :  
           self.err_msg += "URLError"
        except urllib.error.HTTPError as e :  
           self.err_msg += e.reason
        except http.client.HTTPException as e:
           self.err_msg += "HTTPException"
        except ConnectionResetError:
           self.err_msg += "ConnectionResetError"
        except IOError:
           self.err_msg += "IOError"
        else:
           data=json.loads(r.read())  
        return data

    def get_data(self,idx=0):
        url_data = "https://kawal-c1.appspot.com/api/c/"+str(idx)
        json_data = self.get_json(url_data)
        data={"data_kawal":json_data}
        return data

