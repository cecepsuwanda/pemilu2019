from mydb import colection_raw_provinsi
from mydb import colection_raw_kabkota
from mydb import colection_raw_kec
from mydb import colection_raw_kel
from mydb import colection_raw_tps
from mydb import colection_raw_detail_tps
from mydb import colection_raw_proses
from mydb import colection_raw_thread
from crawler import data_kpu
from crawler import data_kawal
import proses, threading, uuid


class get_raw:

    @staticmethod
    def get_jml_page(jml_total, jml_perpage):
        jml_page = jml_total // jml_perpage
        if (jml_total % jml_perpage) > 0:
            jml_page += 1
        return jml_page

    @staticmethod
    def hapus_log():
        raw_proses = colection_raw_proses("", "")
        raw_proses.delete_all()
        raw_thread = colection_raw_thread("", "")
        raw_thread.delete_all()

    def get_raw_provinsi(self):
        raw_provinsi = colection_raw_provinsi()
        raw_provinsi.delete_all()
        kpu_provinsi = data_kpu()
        kpu_provinsi = kpu_provinsi.get_data()
        kawal_provinsi = data_kawal()
        kawal_provinsi = kawal_provinsi.get_data()
        raw_provinsi.mycol.insert_one(
            {"data_kpu": kpu_provinsi["data_kpu"], "data_kawal": kawal_provinsi["data_kawal"]})

    def get_raw_kabkota(self):
        col_kpu_kabkota = data_kpu()
        col_kawal_kabkota = data_kawal()
        raw_kabkota = colection_raw_kabkota()
        raw_kabkota.delete_all()
        raw_provinsi = colection_raw_provinsi()
        raw_provinsi = raw_provinsi.mycol.find({})
        for x in raw_provinsi:
            for y in x["data_kpu"]["json_wilayah"]:
                kpu_kabkota = col_kpu_kabkota.get_data([y])
                kawal_kabkota = col_kawal_kabkota.get_data(y)
                raw_kabkota.mycol.insert_one(
                    {"kode": str(y), "data_kpu": kpu_kabkota["data_kpu"], "data_kawal": kawal_kabkota["data_kawal"]})

    def get_raw_kec(self, hps_log, isupdate):
        if hps_log == 1:
            self.hapus_log()
        if isupdate == 0:
            raw_kec = colection_raw_kec()
            raw_kec.delete_all()
        raw_kabkota = colection_raw_kabkota()
        jml = raw_kabkota.count({})
        jml_page = self.get_jml_page(jml, 10)
        thread_list = []
        for i in range(jml_page):
            print("no_page : {}".format(i + 1))
            p = threading.Thread(target=proses.kec, name='proses{}'.format(i), args=(i + 1, 10))
            p.start()
            thread_list.append(p)
            if (len(thread_list) % 2) == 0:
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join(2 * 10)
                thread_list.clear()

    def get_raw_kel(self, hps_log, isupdate):
        if hps_log == 1:
            self.hapus_log()
        if isupdate == 0:
            raw_kel = colection_raw_kel()
            raw_kel.delete_all()
        raw_kec = colection_raw_kec()
        jml = raw_kec.count({})
        jml_page = self.get_jml_page(jml, 100)
        thread_list = []
        for i in range(jml_page):
            print("no_page : {}".format(i + 1))
            p = threading.Thread(target=proses.kel, name='proses{}'.format(i), args=(i + 1, 100))
            p.start()
            thread_list.append(p)
            if (len(thread_list) % 2) == 0:
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join(3 * 60)
                thread_list.clear()

    def get_raw_tps(self, hps_log, isupdate):
        if hps_log == 1:
            self.hapus_log()
        if isupdate == 0:
            raw_tps = colection_raw_tps()
            raw_tps.delete_all()
        raw_kel = colection_raw_kel()
        jml = raw_kel.count({})
        jml_page = self.get_jml_page(jml, 800)
        thread_list = []
        for i in range(jml_page):
            print("no_page : {}".format(i + 1))
            p = threading.Thread(target=proses.tps, name='proses{}'.format(i), args=(i + 1, 800), daemon=True)
            p.start()
            thread_list.append(p)
            if (len(thread_list) % 2) == 0:
                j = 1
                for thread in thread_list:
                    if thread.is_alive() is True:
                        thread.join((30 // j) * 60)
                        j += 1
                thread_list.clear()

    def get_raw_detail_tps(self, hps_log, isupdate):
        if hps_log == 1:
            self.hapus_log()
        if isupdate == 0:
            raw_detail_tps = colection_raw_detail_tps()
            raw_detail_tps.delete_all()
        # raw_proses = colection_raw_proses()
        raw_tps = colection_raw_tps()
        jml = raw_tps.count({})
        jml_page = self.get_jml_page(jml, 800)
        thread_list = []
        for i in range(jml_page):
            print("no_page : {}".format(i + 1))
            kd_proses = str(uuid.uuid4())
            p = threading.Thread(target=proses.detail_tps, name='proses{}'.format(i),
                                 args=(i + 1, 800, kd_proses), daemon=True)
            p.start()
            thread_list.append({'kd_proses': kd_proses, 'p': p})
            if (len(thread_list) % 2) == 0:
                j = 1
                for thread in thread_list:
                    if thread['p'].is_alive() is True:
                        thread['p'].join((60 // j) * 60)
                        j += 1
                thread_list.clear()
