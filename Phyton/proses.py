from mydb import colection_raw_kabkota
from mydb import colection_raw_kec
from mydb import colection_raw_kel
from mydb import colection_raw_tps
from mydb import colection_raw_detail_tps
from mydb import colection_raw_proses

import uuid, time, mthread, threading


def kec(no_page, jml):
    time.sleep(5)
    kd_proses = str(uuid.uuid4())
    raw_proses = colection_raw_proses(kd_proses, "baca_raw_kec")
    raw_proses.update({"page": no_page, "jml": jml, "jml_kabkota": 0, "jml_thread": 0, "jml_proses": 0})
    skip = jml * (no_page - 1)
    raw_kabkota = colection_raw_kabkota()
    raw_kec = colection_raw_kec()
    data1 = raw_kabkota.get_lst_kec(skip, jml)
    data2 = raw_kec.get_kec_inlst(data1)
    data3 = raw_kabkota.get_kec_notinlst(skip, jml, data2)
    i = 0
    j = 0
    k = len(data2)
    thread_list = []
    if len(data1) != len(data2):
        for x in data3:
            for y in x["kec"]:
                data = {'kode_provinsi': x["kode_provinsi"], 'kode_kabkota': str(y)}
                t = threading.Thread(target=mthread.kec, name='thread{}'.format(str(y)), args=(data,))
                t.start()
                thread_list.append(t)
                i += 1
                k += 1
            j += 1;
            raw_proses.update({"jml_kabkota": k, "jml_proses": j, "jml_thread": i})
            jml_thread = len(thread_list)
            if jml_thread > 0:
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join(jml_thread * 2)
                    else:
                        jml_thread -= 1
                thread_list.clear()
    raw_proses.stop()


def kel(no_page, jml):
    time.sleep(5)
    kd_proses = str(uuid.uuid4())
    raw_proses = colection_raw_proses(kd_proses, "baca_raw_kel")
    raw_proses.update({"page": no_page, "jml": jml, "jml_kel": 0, "jml_thread": 0, "jml_proses": 0})
    skip = jml * (no_page - 1)
    raw_kec = colection_raw_kec()
    raw_kel = colection_raw_kel()
    data1 = raw_kec.get_lst_kel(skip, jml)
    data2 = raw_kel.get_kel_inlst(data1)
    data3 = raw_kec.get_kel_notinlst(skip, jml, data2)
    i = 0
    j = 0
    k = len(data2)
    thread_list = []
    if (len(data1) != len(data2)):
        for x in data3:
            for y in x["kel"]:
                data = {'kode_provinsi': x["kode_provinsi"], 'kode_kabkota': x["kode_kabkota"], 'kode_kec': str(y)}
                t = threading.Thread(target=mthread.kel, name='thread{}'.format(str(y)), args=(data,))
                t.start()
                thread_list.append(t)
                i += 1
                k += 1
            j += 1
            raw_proses.update({"jml_kel": k, "jml_proses": j, "jml_thread": i})
            jml_thread = len(thread_list)
            if jml_thread > 0:
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join(jml_thread * 2)
                    else:
                        jml_thread -= 1
                thread_list.clear()
    raw_proses.stop()


def tps(no_page, jml):
    time.sleep(5)
    kd_proses = str(uuid.uuid4())
    raw_proses = colection_raw_proses(kd_proses, "baca_raw_kelurahan")
    raw_proses.update({"page": no_page, "jml": jml, "jml_tps": 0, "jml_thread": 0, "jml_proses": 0})
    skip = jml * (no_page - 1)
    raw_kel = colection_raw_kel()
    raw_tps = colection_raw_tps()
    data1 = raw_kel.get_lst_tps(skip, jml)
    data2 = raw_tps.get_tps_inlst(data1)
    data3 = raw_kel.get_tps_notinlst(skip, jml, data2)
    i = 0
    j = 0
    k = len(data2)
    thread_list = []
    if (len(data1) != len(data2)):
        for x in data3:
            for y in x["tps"]:
                data = {'kode_provinsi': x["kode_provinsi"], 'kode_kabkota': x["kode_kabkota"],
                        'kode_kec': x["kode_kec"], 'kode_kelurahan': str(y)}
                t = threading.Thread(target=mthread.tps, name='thread{}'.format(str(y)), args=(data,), daemon=True)
                t.start()
                thread_list.append(t)
                i += 1
                k += 1
            j += 1
            raw_proses.update({"jml_tps": k, "jml_proses": j, "jml_thread": i})
            jml_thread = len(thread_list)
            if jml_thread > 0:
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join(jml_thread * 2)
                    else:
                        jml_thread -= 1
                thread_list.clear()
    raw_proses.stop()


def kawal_tps(no_page, jml):
    time.sleep(5)
    kd_proses = str(uuid.uuid4())
    raw_proses = colection_raw_proses(kd_proses, "baca_raw_kelurahan")
    raw_proses.update({"page": no_page, "jml": jml, "jml_tps": 0, "jml_thread": 0})
    skip = jml * (no_page - 1)
    raw_tps = colection_raw_tps()
    data3 = raw_tps.get_data_kawal(skip, jml)
    i = 0
    k = 0
    j = 0
    thread_list = []
    for x in data3:
        for y in x["tps"]:
            t = threading.Thread(target=mthread.kawal_tps, name='thread{}'.format(str(y["kode_tps"])), args=(y,),
                                 daemon=True)
            t.start()
            thread_list.append(t)
            i += 1
            k += 1
        j += 1
        raw_proses.update({"jml_tps": k, "jml_proses": j, "jml_thread": i})
        for thread in thread_list:
            if thread.is_alive() is True:
                thread.join()
        thread_list.clear()
    raw_proses.stop()


def detail_tps(no_page, jml, kd_proses):
    time.sleep(5)
    # raw_thread = colection_raw_thread()
    raw_proses = colection_raw_proses(kd_proses, "baca_raw_tps")
    raw_proses.update({"page": no_page, "jml": jml, "jml_tps": 0, "jml_thread": 0, "jml_proses": 0})
    skip = jml * (no_page - 1)
    raw_tps = colection_raw_tps()
    raw_detail_tps = colection_raw_detail_tps()
    lst_tps1 = raw_tps.get_lst_tps(skip, jml)
    lst_tps2 = raw_detail_tps.get_tps_inlst(lst_tps1)
    i = 1
    j = 0
    k = len(lst_tps2)
    thread_list = []
    if (len(lst_tps1) != len(lst_tps2)):
        lst_tps3 = raw_tps.get_tps_notinlst(skip, jml, lst_tps2)
        for x in lst_tps3:
            for y in x['tps']:
                data = {'kode_provinsi': x["_id"]["kode_provinsi"],
                        'kode_kabkota': x["_id"]["kode_kabkota"],
                        'kode_kec': x["_id"]["kode_kec"],
                        'kode_kelurahan': x["_id"]["kode_kel"],
                        'kode_tps': str(y["json_wilayah"]["kode_tps"]),
                        'json_wilayah': y["json_wilayah"],
                        'data_kawal': y['data_kawal']}
                t = threading.Thread(target=mthread.detail_tps,
                                     name='thread{}'.format(str(y["json_wilayah"]["kode_tps"])),
                                     args=(data,),
                                     daemon=True)
                t.start()
                thread_list.append(t)
                i += 1
                k += 1
            raw_proses.update({"jml_tps": k, "jml_proses": j, "jml_thread": (i - 1)})
            jml_thread = len(thread_list)
            if jml_thread > 0:
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join(jml_thread * 30)
                else:
                    jml_thread -= 1
                thread_list.clear()
            j += 1
        else:
            lst_tps3 = raw_tps.get_tps_notinlst_org(skip, jml, lst_tps2)
            for x in lst_tps3:
                for y in x['tps']:
                    data = {'kode_provinsi': x["_id"]["kode_provinsi"],
                            'kode_kabkota': x["_id"]["kode_kabkota"],
                            'kode_kec': x["_id"]["kode_kec"],
                            'kode_kelurahan': x["_id"]["kode_kel"],
                            'kode_tps': str(y["json_wilayah"]["kode_tps"]),
                            'json_wilayah': y["json_wilayah"]}
                    t = threading.Thread(target=mthread.detail_tps_org,
                                         name='thread{}'.format(str(y["json_wilayah"]["kode_tps"])),
                                         args=(data,),
                                         daemon=True)
                    t.start()
                    thread_list.append(t)
                    i += 1
                    k += 1
                raw_proses.update({"jml_tps": k, "jml_proses": j, "jml_thread": (i - 1)})
                jml_thread = len(thread_list)
                if jml_thread > 0:
                    for thread in thread_list:
                        if thread.is_alive() is True:
                            thread.join(jml_thread * 30)
                    else:
                        jml_thread -= 1
                    thread_list.clear()
                j += 1
    raw_proses.stop()
