from mydb import colection_raw_kec
from mydb import colection_raw_kel
from mydb import colection_raw_tps
from mydb import colection_raw_detail_tps
from mydb import colection_raw_thread
from crawler import data_kpu
from crawler import data_kawal

import uuid


def kec(data):
    kd_thread = str(uuid.uuid4())
    raw_thread = colection_raw_thread(kd_thread, data["kode_kabkota"])
    raw_kec = colection_raw_kec()
    kpu_kec = data_kpu()
    kawal_kec = data_kawal()
    data_kpu_kec = kpu_kec.get_data([data["kode_provinsi"], data["kode_kabkota"]])
    err_msg = kpu_kec.err_msg
    data_kawal_kec = kawal_kec.get_data(data["kode_kabkota"])
    err_msg += kawal_kec.err_msg
    if (err_msg == ""):
        tmp_data = {"kode": str(data['kode_kabkota']), "kode_provinsi": str(data["kode_provinsi"]),
                    "data_kpu": data_kpu_kec["data_kpu"], "data_kawal": data_kawal_kec["data_kawal"]}
        raw_kec.mycol.update_one({"kode": str(data['kode_kabkota'])}, {"$set": tmp_data}, upsert=True)
        # raw_kec.mycol.insert_one(tmp_data)
    else:
        raw_thread.set_error_msg(err_msg)
    raw_thread.stop()


def kel(data):
    kd_thread = str(uuid.uuid4())
    raw_thread = colection_raw_thread(kd_thread, data["kode_kec"])
    raw_kelurahan = colection_raw_kel()
    kpu_kel = data_kpu()
    kawal_kel = data_kawal()
    data_kpu_kel = kpu_kel.get_data([data["kode_provinsi"], data["kode_kabkota"], data["kode_kec"]])
    err_msg = kpu_kel.err_msg
    data_kawal_kel = kawal_kel.get_data(data["kode_kec"])
    err_msg += kawal_kel.err_msg
    if (err_msg == ""):
        tmp_data = {"kode": str(data['kode_kec']), "kode_provinsi": str(data["kode_provinsi"]),
                    "kode_kabkota": str(data["kode_kabkota"]), "data_kpu": data_kpu_kel["data_kpu"],
                    "data_kawal": data_kawal_kel["data_kawal"]}
        raw_kelurahan.mycol.update_one({"kode": str(data['kode_kec'])}, {"$set": tmp_data}, upsert=True)
        # raw_kelurahan.mycol.insert_one(tmp_data)
    else:
        raw_thread.set_error_msg(err_msg)
    raw_thread.stop()


def olah_data_kawal_tps(data_kawal_tps):
    tmp1 = {}
    for key in data_kawal_tps:
        if key == 'data':
            tmp1[key] = {}
            for key1 in data_kawal_tps[key]:
                tmp1[key][key1] = {}
                for key2 in data_kawal_tps[key][key1]:
                    if key2 == 'photos':
                        tmp1[key][key1][key2] = []
                        for key3 in data_kawal_tps[key][key1][key2]:
                            tmp2 = {}
                            tmp2['photo'] = key3
                            for key4 in data_kawal_tps[key][key1][key2][key3]:
                                tmp2[key4] = data_kawal_tps[key][key1][key2][key3][key4]
                            tmp1[key][key1][key2].append(tmp2)
                    else:
                        tmp1[key][key1][key2] = data_kawal_tps[key][key1][key2]
        else:
            tmp1[key] = data_kawal_tps[key]
    return tmp1


def tps(data):
    kd_thread = str(uuid.uuid4())
    raw_thread = colection_raw_thread(kd_thread, data["kode_kelurahan"])
    raw_tps = colection_raw_tps()
    kpu_tps = data_kpu()
    kawal_tps = data_kawal()
    data_kpu_tps = kpu_tps.get_data(
        [data["kode_provinsi"], data["kode_kabkota"], data["kode_kec"], data["kode_kelurahan"]])
    err_msg = kpu_tps.err_msg
    data_kawal_tps = kawal_tps.get_data(data["kode_kelurahan"])
    hsl_olah = olah_data_kawal_tps(data_kawal_tps["data_kawal"])
    err_msg += kawal_tps.err_msg
    if err_msg == '':
        tmp_data = {"kode": str(data['kode_kelurahan']),
                    "kode_provinsi": str(data["kode_provinsi"]),
                    "kode_kabkota": str(data["kode_kabkota"]),
                    "kode_kec": str(data["kode_kec"]),
                    "data_kpu": data_kpu_tps["data_kpu"],
                    "data_kawal": hsl_olah
                    }
        raw_tps.mycol.update_one({"kode": str(data['kode_kelurahan'])}, {"$set": tmp_data}, upsert=True)
        # raw_tps.mycol.insert_one(tmp_data)
    else:
        raw_thread.set_error_msg(err_msg)
    raw_thread.stop()


def kawal_tps(data):
    kd_thread = str(uuid.uuid4())
    raw_thread = colection_raw_thread(kd_thread, data["kode_tps"])
    raw_detail_tps = colection_raw_detail_tps()
    tmp_data = {"data_kawal": data["data_kawal"]}
    raw_detail_tps.mycol.update_one({"kode": str(data['kode_tps'])}, {"$set": tmp_data})
    raw_thread.stop()


def detail_tps(data):
    kd_thread = str(uuid.uuid4())
    raw_thread = colection_raw_thread(kd_thread, data["kode_tps"])
    raw_detail_tps = colection_raw_detail_tps()
    kpu_detail_tps = data_kpu()
    data_kpu_detail_tps = kpu_detail_tps.get_data(
        [data["kode_provinsi"], data["kode_kabkota"], data["kode_kec"], data["kode_kelurahan"], data['kode_tps']])
    err_msg = kpu_detail_tps.err_msg
    if err_msg == '':
        data_kpu_detail_tps["data_kpu"]["json_wilayah"]=data["json_wilayah"]
        tmp_data = {"kode": str(data['kode_tps']),
                    "kode_provinsi": str(data["kode_provinsi"]),
                    "kode_kabkota": str(data["kode_kabkota"]),
                    "kode_kec": str(data["kode_kec"]),
                    "kode_kelurahan": str(data["kode_kelurahan"]),
                    "data_kpu": data_kpu_detail_tps["data_kpu"],
                    "data_kawal": data["data_kawal"]}
        raw_detail_tps.mycol.update_one({"kode": str(data['kode_tps'])}, {"$set": tmp_data}, upsert=True)
        # raw_detail_tps.mycol.insert_one(tmp_data)
    else:
        raw_thread.set_error_msg(err_msg)
    raw_thread.stop()

def detail_tps_org(data):
    kd_thread = str(uuid.uuid4())
    raw_thread = colection_raw_thread(kd_thread, data["kode_tps"])
    raw_detail_tps = colection_raw_detail_tps()
    kpu_detail_tps = data_kpu()
    data_kpu_detail_tps = kpu_detail_tps.get_data(
        [data["kode_provinsi"], data["kode_kabkota"], data["kode_kec"], data["kode_kelurahan"], data['kode_tps']])
    err_msg = kpu_detail_tps.err_msg
    if err_msg == '':
        data_kpu_detail_tps["data_kpu"]["json_wilayah"] = data["json_wilayah"]
        tmp_data = {"kode": str(data['kode_tps']),
                    "kode_provinsi": str(data["kode_provinsi"]),
                    "kode_kabkota": str(data["kode_kabkota"]),
                    "kode_kec": str(data["kode_kec"]),
                    "kode_kelurahan": str(data["kode_kelurahan"]),
                    "data_kpu": data_kpu_detail_tps["data_kpu"]}
        raw_detail_tps.mycol.update_one({"kode": str(data['kode_tps'])}, {"$set": tmp_data}, upsert=True)
        # raw_detail_tps.mycol.insert_one(tmp_data)
    else:
        raw_thread.set_error_msg(err_msg)
    raw_thread.stop()
