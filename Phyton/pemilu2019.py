from mydb import colection_raw_tps
from mydb import colection_raw_thread
from backup_db import myexport
from backup_db import myimport
from raw_data import get_raw
import proses, threading,time


def get_kawal_detail_tps(hps_log, isupdate):
    if hps_log == 1:
        hapus_log()
    if isupdate == 0:
        raw_tps = colection_raw_tps()
        raw_tps.delete_all()
    raw_tps = colection_raw_tps()
    jml = raw_tps.count({})
    jml_page = jml // 1000
    if (jml % 1000) > 0:
        jml_page += 1
    thread_list = []
    for i in range(jml_page):
        print("no_page : {}".format(i + 1))
        p = threading.Thread(target=proses.kawal_tps, name='proses{}'.format(i), args=(i + 1, 1000), daemon=True)
        p.start()
        # p.join()
        thread_list.append(p)
        if (len(thread_list) % 2) == 0:
            j = 1
            for thread in thread_list:
                if thread.is_alive() is True:
                    thread.join()
                    j += 1
            thread_list.clear()


def backup(isimport, isexport, table):
    if isexport == 1:
        my_export = myexport()
        if table['raw_provinsi'] == 1:
            print('Export Provinsi')
            my_export.raw_provinsi()

        if table['raw_kabkota'] == 1:
            print('Export Kabkota')
            my_export.raw_kabkota()

        if table['raw_kec'] == 1:
            print('Export Kecamatan')
            my_export.raw_kec()

        if table['raw_kel'] == 1:
            print('Export Kelurahan')
            my_export.raw_kel()

        if table['raw_tps'] == 1:
            print('Export TPS')
            my_export.raw_tps()

        if table['raw_detail_tps'] == 1:
            print('Export Detail TPS')
            my_export.raw_detail_tps()

    if isimport == 1:
        my_import = myimport()
        if table['raw_provinsi'] == 1:
            print('Import Provinsi')
            my_import.raw_provinsi()

        if table['raw_kabkota'] == 1:
            print('Import Kabkota')
            my_import.raw_kabkota()

        if table['raw_kec'] == 1:
            print('Import Kecamatan')
            my_import.raw_kec()

        if table['raw_kel'] == 1:
            print('Import Kelurahan')
            my_import.raw_kel()

        if table['raw_tps'] == 1:
            print('Import TPS')
            my_import.raw_tps()

        if table['raw_detail_tps'] == 1:
            print('Import Detail TPS')
            my_import.raw_detail_tps()


if __name__ == '__main__':
    menu = {'provinsi': {'kerjakan': 0}, 'kabkota': {'kerjakan': 0},
            'kec': {'kerjakan': 1, 'hps_log': 1, 'isupdate': 0},
            'kel': {'kerjakan': 0, 'hps_log': 1, 'isupdate': 0},
            'tps': {'kerjakan': 0, 'hps_log': 1, 'isupdate': 1},
            'detail_tps': {'kerjakan': 0, 'hps_log': 1, 'isupdate': 1},
            'kawal_detail_tps': {'kerjakan': 0, 'hps_log': 1, 'isupdate': 1},
            'backup': {'kerjakan': 0, 'isimport': 1, 'isexport': 0,
                       'table': {'raw_provinsi': 1, 'raw_kabkota': 1, 'raw_kec': 1, 'raw_kel': 1, 'raw_tps': 0,
                                 'raw_detail_tps': 0}}
            }
    raw_thread = colection_raw_thread("","")
    raw_data = get_raw()
    if menu["provinsi"]['kerjakan'] == 1:
        print("Baca Data Provinsi")
        raw_data.get_raw_provinsi()
        time.sleep(10)
    if menu["kabkota"]['kerjakan'] == 1:
        print("Baca Data Kabkota")
        raw_data.get_raw_kabkota()
        time.sleep(10)
    if menu["kec"]['kerjakan'] == 1:
        print("Baca Data Kecamatan")
        raw_data.get_raw_kec(menu["kec"]['hps_log'], menu["kec"]['isupdate'])
        jml = raw_thread.jml_error()
        if jml > 0:
            print("Baca Ulang Data Kecamatan")
            raw_data.get_raw_kec(1, 1)
        time.sleep(10)
    if menu["kel"]['kerjakan'] == 1:
        print("Baca Data Kelurahan")
        raw_data.get_raw_kel(menu["kel"]['hps_log'], menu["kel"]['isupdate'])
        jml = raw_thread.jml_error()
        if jml > 0:
            print("Baca Ulang Data Kelurahan")
            raw_data.get_raw_kel(1, 1)
        time.sleep(10)
    if menu["tps"]['kerjakan'] == 1:
        print("Baca Data TPS")
        raw_data.get_raw_tps(menu["tps"]['hps_log'], menu["tps"]['isupdate'])
        jml = raw_thread.jml_error()
        if jml > 0:
            print("Baca Ulang Data TPS")
            raw_data.get_raw_tps(1, 1)
        time.sleep(10)
    if menu["detail_tps"]['kerjakan'] == 1:
        print("Baca Data Detail TPS")
        raw_data.get_raw_detail_tps(menu["detail_tps"]['hps_log'], menu["detail_tps"]['isupdate'])
        jml = raw_thread.jml_error()
        if jml > 0:
            print("Baca Ulang Data Detail TPS")
            raw_data.get_raw_detail_tps(1, 1)
        time.sleep(10)
    if menu["kawal_detail_tps"]['kerjakan'] == 1:
        print("Bacakawal Data Detail TPS")
        get_kawal_detail_tps(menu["detail_tps"]['hps_log'], menu["detail_tps"]['isupdate'])
        time.sleep(10)
    if menu["backup"]['kerjakan'] == 1:
        print("Backup")
        backup(menu["backup"]['isimport'], menu["backup"]['isexport'], menu["backup"]['table'])
