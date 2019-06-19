import pymongo, time


class mongodb:
    myclient = pymongo.MongoClient("mongodb://localhost:27017/")

    def __init__(self, db, collection):
        mydb = mongodb.myclient[db]
        self.mycol = mydb[collection]

    def count(self, query):
        return self.mycol.find(query).count()

    def delete_all(self):
        self.mycol.delete_many({})

    def get_doc(self, query, no_page, jml_doc):
        skip = jml_doc * (no_page - 1)
        cursor = self.mycol.find(query).skip(skip).limit(jml_doc)
        x = []
        for i in cursor:
            x.append(i)
        return x


class colection_raw_provinsi(mongodb):

    def __init__(self):
        super().__init__("kpu_pemilu2019", "raw_provinsi")


class colection_raw_kabkota(mongodb):

    def __init__(self):
        super().__init__("kpu_pemilu2019", "raw_kabkota")

    def get_lst_kec(self, skip, jml):
        data = self.mycol.aggregate(
            [
                {
                    '$skip': skip
                }, {
                '$limit': jml
            }, {
                '$group': {
                    '_id': None,
                    'kec': {
                        '$mergeObjects': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$project': {
                    'kec': {
                        '$objectToArray': '$kec'
                    }
                }
            }, {
                '$project': {
                    'kec': '$kec.k'
                }
            }
            ])
        data = list(data)
        if (len(data) > 0):
            data = data[0]['kec']
        return data

    def get_kec_notinlst(self, skip, limit, lst_kec):
        data = self.mycol.aggregate([
            {
                '$skip': skip
            }, {
                '$limit': limit
            }, {
                '$project': {
                    'kode_provinsi': '$kode',
                    'kec': {
                        '$objectToArray': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$project': {
                    'kode_provinsi': '$kode_provinsi',
                    'kec': '$kec.k'
                }
            }, {
                '$project': {
                    'kode_provinsi': '$kode_provinsi',
                    'kec': {
                        '$filter': {
                            'input': '$kec',
                            'as': 'kec',
                            'cond': {
                                '$not': {
                                    '$in': [
                                        '$$kec', lst_kec
                                    ]
                                }
                            }
                        }
                    }
                }
            }, {
                '$match': {
                    'kec': {
                        '$not': {
                            '$size': 0
                        }
                    }
                }
            }
        ])
        data = list(data)
        return data


class colection_raw_kec(mongodb):

    def __init__(self):
        super().__init__("kpu_pemilu2019", "raw_kec")

    def get_kec_inlst(self, lst_kec):
        data = self.mycol.aggregate([
            {
                '$match': {
                    'kode': {
                        '$in': lst_kec
                    }
                }
            }, {
                '$group': {
                    '_id': None,
                    'kec': {
                        '$addToSet': '$kode'
                    }
                }
            }
        ])
        data = list(data)
        if len(data) > 0:
            data = data[0]['kec']
        return data

    def get_lst_kel(self, skip, jml):
        data = self.mycol.aggregate(
            [
                {
                    '$skip': skip
                }, {
                '$limit': jml
            }, {
                '$group': {
                    '_id': None,
                    'kel': {
                        '$mergeObjects': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$project': {
                    'kel': {
                        '$objectToArray': '$kel'
                    }
                }
            }, {
                '$project': {
                    'kel': '$kel.k'
                }
            }
            ])
        data = list(data)
        if len(data) > 0:
            data = data[0]['kel']
        return data

    def get_kel_notinlst(self, skip, limit, lst_kel):
        data = self.mycol.aggregate([
            {
                '$skip': skip
            }, {
                '$limit': limit
            }, {
                '$project': {
                    'kode_kabkota': '$kode',
                    'kode_provinsi': '$kode_provinsi',
                    'kel': {
                        '$objectToArray': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$project': {
                    'kode_kabkota': '$kode_kabkota',
                    'kode_provinsi': '$kode_provinsi',
                    'kel': '$kel.k'
                }
            }, {
                '$project': {
                    'kode_kabkota': '$kode_kabkota',
                    'kode_provinsi': '$kode_provinsi',
                    'kel': {
                        '$filter': {
                            'input': '$kel',
                            'as': 'kel',
                            'cond': {
                                '$not': {
                                    '$in': [
                                        '$$kel', lst_kel
                                    ]
                                }
                            }
                        }
                    }
                }
            }, {
                '$match': {
                    'kel': {
                        '$not': {
                            '$size': 0
                        }
                    }
                }
            }
        ])
        data = list(data)
        return data


class colection_raw_kel(mongodb):

    def __init__(self):
        super().__init__("kpu_pemilu2019", "raw_kelurahan")

    def get_kel_inlst(self, lst_kel):
        data = self.mycol.aggregate([
            {
                '$match': {
                    'kode': {
                        '$in': lst_kel
                    }
                }
            }, {
                '$group': {
                    '_id': None,
                    'kel': {
                        '$addToSet': '$kode'
                    }
                }
            }
        ])
        data = list(data)
        if len(data) > 0:
            data = data[0]['kel']
        return data

    def get_lst_tps(self, skip, jml):
        data = self.mycol.aggregate(
            [
                {
                    '$skip': skip
                }, {
                '$limit': jml
            }, {
                '$group': {
                    '_id': None,
                    'tps': {
                        '$mergeObjects': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$project': {
                    'tps': {
                        '$objectToArray': '$tps'
                    }
                }
            }, {
                '$project': {
                    'tps': '$tps.k'
                }
            }
            ])
        data = list(data)
        return data[0]['tps']

    def get_tps_notinlst(self, skip, limit, lst_tps):
        data = self.mycol.aggregate([
            {
                '$skip': skip
            }, {
                '$limit': limit
            }, {
                '$project': {
                    'kode_kec': '$kode',
                    'kode_kabkota': '$kode_kabkota',
                    'kode_provinsi': '$kode_provinsi',
                    'tps': {
                        '$objectToArray': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$project': {
                    'kode_kec': '$kode_kec',
                    'kode_kabkota': '$kode_kabkota',
                    'kode_provinsi': '$kode_provinsi',
                    'tps': '$tps.k'
                }
            }, {
                '$project': {
                    'kode_kec': '$kode_kec',
                    'kode_kabkota': '$kode_kabkota',
                    'kode_provinsi': '$kode_provinsi',
                    'tps': {
                        '$filter': {
                            'input': '$tps',
                            'as': 'tps',
                            'cond': {
                                '$not': {
                                    '$in': [
                                        '$$tps', lst_tps
                                    ]
                                }
                            }
                        }
                    }
                }
            }, {
                '$match': {
                    'tps': {
                        '$not': {
                            '$size': 0
                        }
                    }
                }
            }
        ])
        data = list(data)
        return data


class colection_raw_tps(mongodb):

    def __init__(self):
        super().__init__("kpu_pemilu2019", "raw_tps")

    def get_data_kawal(self, skip, limit):
        data = self.mycol.aggregate([
            {
                '$skip': skip
            }, {
                '$limit': limit
            }, {
                '$project': {
                    'kode_kel': '$kode',
                    'data_kpu': {
                        '$objectToArray': '$data_kpu.json_wilayah'
                    },
                    'data_kawal': {
                        '$objectToArray': '$data_kawal.data'
                    }
                }
            }, {
                '$unwind': {
                    'path': '$data_kpu',
                    'includeArrayIndex': 'data_kpu_index'
                }
            }, {
                '$unwind': {
                    'path': '$data_kawal',
                    'includeArrayIndex': 'data_kawal_index'
                }
            }, {
                '$match': {
                    'data_kawal.k': {
                        '$not': {
                            '$eq': '0'
                        }
                    }
                }
            }, {
                '$project': {
                    'kode_kel': 1,
                    'kode_tps': '$data_kpu.k',
                    'nama_tps': '$data_kpu.v.nama',
                    'idx_kawal': {
                        '$concat': [
                            'TPS ', '$data_kawal.k'
                        ]
                    },
                    'data_kawal': '$data_kawal'
                }
            }, {
                '$project': {
                    'kode_kel': 1,
                    'kode_tps': 1,
                    'data_kawal': 1,
                    'compare': {
                        '$cmp': [
                            '$nama_tps', '$idx_kawal'
                        ]
                    }
                }
            }, {
                '$match': {
                    'compare': 0
                }
            }, {
                '$group': {
                    '_id': '$kode_kel',
                    'tps': {
                        '$addToSet': {
                            'kode_tps': '$kode_tps',
                            'data_kawal': '$data_kawal'
                        }
                    }
                }
            }
        ])
        data = list(data)
        return data

    def get_tps_inlst(self, lst_tps):
        data = self.mycol.aggregate([
            {
                '$match': {
                    'kode': {
                        '$in': lst_tps
                    }
                }
            }, {
                '$group': {
                    '_id': None,
                    'tps': {
                        '$addToSet': '$kode'
                    }
                }
            }
        ])
        data = list(data)
        if len(data) > 0:
            data = data[0]['tps']
        return data

    def get_lst_tps(self, skip, limit):
        pipeline = []
        if skip > 0:
            pipeline.append({'$skip': skip})
        pipeline.append({'$limit': limit})
        pipeline.append({'$group': {'_id': 'null', 'tps': {'$mergeObjects': "$data_kpu.json_wilayah"}}})
        pipeline.append({'$project': {'tps': {'$objectToArray': "$tps"}}})
        pipeline.append({'$project': {'tps': "$tps.k"}})
        hsl_agr_tps = self.mycol.aggregate(pipeline)
        hsl_agr_tps = list(hsl_agr_tps)
        if len(hsl_agr_tps) > 0:
            hsl_agr_tps = hsl_agr_tps[0]['tps']
        return hsl_agr_tps

    def get_tps_notinlst_org(self, skip, limit, lst_tps):
        hsl_agr_tps = self.mycol.aggregate([
            {
                '$skip': skip
            }, {
                '$limit': limit
            }, {
                '$project': {
                    'kode_kel': '$kode',
                    'kode_kec': 1,
                    'kode_kabkota': 1,
                    'kode_provinsi': 1,
                    'kpu_wilayah': {
                        '$objectToArray': '$data_kpu.json_wilayah'
                    }
                }
            }, {
                '$unwind': {
                    'path': '$kpu_wilayah'
                }
            }, {
                '$match': {
                    'kpu_wilayah.k': {
                        '$not': {
                            '$in': lst_tps
                        }
                    }
                }
            }, {
                '$group': {
                    '_id': {
                        'kode_provinsi': '$kode_provinsi',
                        'kode_kabkota': '$kode_kabkota',
                        'kode_kec': '$kode_kec',
                        'kode_kel': '$kode_kel'
                    },
                    'tps': {
                        '$addToSet': {
                            'json_wilayah': {
                                'kode_tps': '$kpu_wilayah.k',
                                'nama_tps': '$kpu_wilayah.v.nama'
                            }
                        }
                    }
                }
            }
        ])
        hsl_agr_tps = list(hsl_agr_tps)
        return hsl_agr_tps

    def get_tps_notinlst(self, skip, limit, lst_tps):
        hsl_agr_tps = self.mycol.aggregate([
            {
                '$skip': skip
            }, {
                '$limit': limit
            }, {
                '$project': {
                    'kode_kel': '$kode',
                    'kode_kec': 1,
                    'kode_kabkota': 1,
                    'kode_provinsi': 1,
                    'kpu_wilayah': {
                        '$objectToArray': '$data_kpu.json_wilayah'
                    },
                    'kawal_data': {
                        '$objectToArray': '$data_kawal.data'
                    }
                }
            }, {
                '$unwind': {
                    'path': '$kpu_wilayah'
                }
            }, {
                '$unwind': {
                    'path': '$kawal_data'
                }
            }, {
                '$match': {
                    'kawal_data.k': {
                        '$not': {
                            '$eq': '0'
                        }
                    }
                }
            }, {
                '$project': {
                    'kode_kel': 1,
                    'kode_kec': 1,
                    'kode_kabkota': 1,
                    'kode_provinsi': 1,
                    'kpu_wilayah': 1,
                    'kpu_suara': 1,
                    'kawal_data': 1,
                    'compare1': {
                        '$cmp': [
                            '$kpu_wilayah.v.nama', {
                                '$concat': [
                                    'TPS ', '$kawal_data.k'
                                ]
                            }
                        ]
                    },
                    'compare2': {
                        '$cmp': [
                            '$kpu_wilayah.v.nama', {
                                '$concat': [
                                    'TPS 0', '$kawal_data.k'
                                ]
                            }
                        ]
                    }
                }
            }, {
                '$match': {
                    '$or': [
                        {
                            'compare1': 0
                        }, {
                            'compare2': 0
                        }
                    ]
                }
            }, {
                '$match': {
                    'kpu_wilayah.k': {
                        '$not': {
                            '$in': lst_tps
                        }
                    }
                }
            }, {
                '$group': {
                    '_id': {
                        'kode_provinsi': '$kode_provinsi',
                        'kode_kabkota': '$kode_kabkota',
                        'kode_kec': '$kode_kec',
                        'kode_kel': '$kode_kel'
                    },
                    'tps': {
                        '$addToSet': {
                            'json_wilayah': {
                                'kode_tps': '$kpu_wilayah.k',
                                'nama_tps': '$kpu_wilayah.v.nama'
                            },
                            'data_kawal': '$kawal_data.v'
                        }
                    }
                }
            }
        ])
        hsl_agr_tps = list(hsl_agr_tps)
        return hsl_agr_tps


class colection_raw_detail_tps(mongodb):

    def __init__(self):
        super().__init__("kpu_pemilu2019", "raw_detail_tps")

    def get_tps_inlst(self, lst_tps):
        pipeline = []
        pipeline.append({'$match': {'kode': {'$in': lst_tps}}})
        pipeline.append({'$group': {'_id': 'null', 'tps': {'$addToSet': "$kode"}}})
        hsl_agr_detail_tps = self.mycol.aggregate(pipeline)
        hsl_agr_detail_tps = list(hsl_agr_detail_tps)
        if len(hsl_agr_detail_tps) > 0:
            hsl_agr_detail_tps = hsl_agr_detail_tps[0]['tps']
        return hsl_agr_detail_tps


class colection_raw_proses(mongodb):

    def __init__(self, kode, name):
        super().__init__("kpu_pemilu2019", "raw_proses")
        if kode != "" and name != "":
            self.kode = kode
            self.name = name
            localtime = time.asctime(time.localtime(time.time()))
            self.mycol.insert_one(
                {"kode": self.kode, "name": self.name, "selesai": 0, "time_mulai": localtime})

    def update(self, attr):
        self.mycol.update_one({"kode": self.kode}, {"$set": attr})

    def stop(self):
        localtime = time.asctime(time.localtime(time.time()))
        self.mycol.update_one({"kode": self.kode}, {"$set": {"selesai": 1, "time_selesai": localtime}})


class colection_raw_thread(mongodb):

    def __init__(self, kode, name):
        super().__init__("kpu_pemilu2019", "raw_thread")
        if kode != "" and name != "":
            self.kode = kode
            self.name = name
            localtime = time.asctime(time.localtime(time.time()))
            self.mycol.insert_one(
                {"kode": self.kode, "name": self.name, "selesai": 0, "error": "",
                 "time_mulai": localtime})

    def set_error_msg(self, msg):
        self.mycol.update_one({"kode": self.kode}, {"$set": {"error": msg}})

    def stop(self):
        localtime = time.asctime(time.localtime(time.time()))
        self.mycol.update_one({"kode": self.kode}, {"$set": {"selesai": 1, "time_selesai": localtime}})

    def jml_error(self):
        hsl = self.mycol.aggregate([
            {
                '$match': {
                    'error': {
                        '$not': {
                            '$eq': ''
                        }
                    }
                }
            }, {
                '$count': 'jml'
            }
        ])
        hsl = list(hsl)
        jml = 0
        if len(hsl) > 0:
            jml = hsl[0]['jml']
        return jml
