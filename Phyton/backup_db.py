from mydb import colection_raw_provinsi
from mydb import colection_raw_kabkota
from mydb import colection_raw_kec
from mydb import colection_raw_kel
from mydb import colection_raw_tps
from mydb import colection_raw_detail_tps

import json, os, fnmatch


class myexport:

    def get_jml_page(self, jml_total, jml_perpage):
        jml_page = jml_total // jml_perpage
        if (jml_total % jml_perpage) > 0:
            jml_page += 1
        return jml_page

    def raw_provinsi(self):
        raw_provinsi = colection_raw_provinsi()
        data = raw_provinsi.mycol.find({}, {'_id': 0})
        with open('db/raw_provinsi.json', 'w') as outfile:
            json.dump(list(data), outfile, indent=4)

    def raw_kabkota(self):
        raw_kabkota = colection_raw_kabkota()
        data = raw_kabkota.mycol.find({}, {'_id': 0})
        with open('db/raw_kabkota.json', 'w') as outfile:
            json.dump(list(data), outfile, indent=4)

    def raw_kec(self):
        raw_kec = colection_raw_kec()
        data = raw_kec.mycol.find({}, {'_id': 0})
        with open('db/raw_kec.json', 'w') as outfile:
            json.dump(list(data), outfile, indent=4)

    def raw_kel(self):
        for file_name in os.listdir('db/raw_kel/'):
            if fnmatch.fnmatch(file_name, 'raw_kel_part_*.json'):
                os.remove('db/raw_kel/' + file_name)
        raw_kel = colection_raw_kel()
        jml = raw_kel.count({})
        jml_page = self.get_jml_page(jml, 1000)
        for i in range(jml_page):
            skip = 1000 * i
            data = raw_kel.mycol.find({}, {'_id': 0}).skip(skip).limit(1000)
            with open("db/raw_kel/raw_kel_part_{}.json".format(i), 'w') as outfile:
                json.dump(list(data), outfile, indent=4)

    def raw_tps(self):
        for file_name in os.listdir('db/raw_tps/'):
            if fnmatch.fnmatch(file_name, 'raw_tps_part_*.json'):
                os.remove('db/raw_tps/' + file_name)
        raw_tps = colection_raw_tps()
        jml = raw_tps.count({})
        jml_page = self.get_jml_page(jml, 4000)
        for i in range(jml_page):
            skip = 4000 * i
            data = raw_tps.mycol.find({}, {'_id': 0}).skip(skip).limit(4000)
            with open("db/raw_tps/raw_tps_part_{}.json".format(i), 'w') as outfile:
                json.dump(list(data), outfile, indent=4)

    def raw_detail_tps(self):
        for file_name in os.listdir('db/raw_detail_tps/'):
            if fnmatch.fnmatch(file_name, 'raw_detail_tps_part_*.json'):
                os.remove('db/raw_detail_tps/' + file_name)
        raw_detail_tps = colection_raw_detail_tps()
        jml = raw_detail_tps.count({})
        jml_page = self.get_jml_page(jml, 4000)
        for i in range(jml_page):
            skip = 4000 * i
            data = raw_detail_tps.mycol.find({}, {'_id': 0}).skip(skip).limit(4000)
            with open("db/raw_detail_tps/raw_detail_tps_part_{}.json".format(i), 'w') as outfile:
                json.dump(list(data), outfile, indent=4)


class myimport:

    def raw_provinsi(self):
        raw_provinsi = colection_raw_provinsi()
        raw_provinsi.delete_all()
        with open('db/raw_provinsi.json') as json_file:
            data = json.load(json_file)
            raw_provinsi.mycol.insert_one(data[0])

    def raw_kabkota(self):
        raw_kabkota = colection_raw_kabkota()
        raw_kabkota.delete_all()
        with open('db/raw_kabkota.json') as json_file:
            data = json.load(json_file)
            raw_kabkota.mycol.insert_many(data)

    def raw_kec(self):
        raw_kec = colection_raw_kec()
        raw_kec.delete_all()
        with open('db/raw_kec.json') as json_file:
            data = json.load(json_file)
            raw_kec.mycol.insert_many(data)

    def raw_kel(self):
        raw_kel = colection_raw_kel()
        raw_kel.delete_all()
        for i in range(8):
            with open("db/raw_kel/raw_kel_part_{}.json".format(i)) as json_file:
                data = json.load(json_file)
                raw_kel.mycol.insert_many(data)

    def raw_tps(self):
        raw_tps = colection_raw_tps()
        raw_tps.delete_all()
        for i in range(21):
            with open("db/raw_tps/raw_tps_part_{}.json".format(i)) as json_file:
                data = json.load(json_file)
                raw_tps.mycol.insert_many(data)

    def raw_detail_tps(self):
        raw_detail_tps = colection_raw_detail_tps()
        raw_detail_tps.delete_all()
        for i in range(204):
            with open("db/raw_detail_tps/raw_detail_tps_part_{}.json".format(i)) as json_file:
                data = json.load(json_file)
                raw_detail_tps.mycol.insert_many(data)
