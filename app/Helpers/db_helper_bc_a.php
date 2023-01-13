<?php

function gettotal($db, $kdprogram, $kdgiat, $kdoutput, $param, $field1 = 0)
{
    $db      = \Config\Database::connect($db);

    switch ($param) {

            //output
        case 'outputvol':
            $builder = $db->query("SELECT SUM(REPLACE(pkt.vol,',','.')) vol FROM paket pkt WHERE 
        pkt.kdprogram = '$kdprogram' AND pkt.kdgiat = '$kdgiat' AND pkt.kdoutput = '$kdoutput' limit 1
        ")->getRow();
            goto out;
            break;
        case 'outputpg':
            $builder = $db->query("SELECT SUM(pg) vol FROM paket pkt WHERE pkt.kdprogram = '$kdprogram' AND pkt.kdgiat = '$kdgiat' AND pkt.kdoutput = '$kdoutput' limit 1
            ")->getRow();
            goto out;
            break;
        case 'outputrealisasi':
            $builder = $db->query("SELECT SUM($field1) vol FROM paket pkt WHERE 
        pkt.kdprogram = '$kdprogram' AND pkt.kdgiat = '$kdgiat'  AND pkt.kdoutput = '$kdoutput' limit 1
        ")->getRow();
            goto out;
            break;

        case 'outputkeu_rn':
            $builder = $db->query(
                "SELECT SUM($field1) / sum(pg) * 100 / 
                (SELECT COUNT($field1) 
                FROM paket
                WHERE
                kdprogram = '$kdprogram'
                AND kdgiat = '$kdgiat'
                AND kdoutput = '$kdoutput'
                limit 1
                ) vol 
                FROM paket pkt 
                WHERE 
            pkt.kdprogram = '$kdprogram' 
            AND pkt.kdgiat = '$kdgiat' 
            AND pkt.kdoutput = '$kdoutput' limit 1
            "
            )->getRow();
            goto out;
            break;

        case 'outputkeu_rl':
            $builder = $db->query(
                "SELECT SUM($field1) / sum(pg) * 100 /
                (SELECT COUNT($field1) 
                FROM paket
                WHERE
                kdprogram = '$kdprogram'
                AND kdgiat = '$kdgiat'
                AND kdoutput = '$kdoutput'
                limit 1
                ) vol 
                FROM paket pkt 
                WHERE 
                pkt.kdprogram = '$kdprogram' 
                AND pkt.kdgiat = '$kdgiat' 
            
                AND pkt.kdoutput = '$kdoutput' limit 1
                "
            )->getRow();
            goto out;
            break;

        case 'outputfis_rn':
            $builder = $db->query(
                "SELECT SUM($field1) /
                (SELECT COUNT($field1) 
                FROM paket
                WHERE
                kdprogram = '$kdprogram'
                AND kdgiat = '$kdgiat'
               
                AND kdoutput = '$kdoutput'
                limit 1
                ) vol 
                FROM paket pkt 
                WHERE 
                pkt.kdprogram = '$kdprogram' 
                AND pkt.kdgiat = '$kdgiat' 
            
                AND pkt.kdoutput = '$kdoutput' limit 1
                "
            )->getRow();
            goto out;
            break;

        case 'outputfis_rl':
            $builder = $db->query(
                "SELECT SUM($field1) / SUM(pg) * 100 /
                (SELECT COUNT($field1) 
                FROM paket
                WHERE
                kdprogram = '$kdprogram'
                AND kdgiat = '$kdgiat'
               
                AND kdoutput = '$kdoutput' limit 1
            
                ) vol 
                FROM paket pkt 
                WHERE 
            pkt.kdprogram = '$kdprogram' 
            AND pkt.kdgiat = '$kdgiat'  
            AND pkt.kdoutput = '$kdoutput' limit 1
            "
            )->getRow();
            goto out;
            break;

            //  kegiatan case
        case 'kegiatanvol':
            $builder = $db->query("SELECT SUM(REPLACE(pkt.vol,',','.')) vol FROM paket pkt WHERE 
            pkt.kdprogram = '$kdprogram' AND pkt.kdgiat = '$kdgiat'  limit 1
            ")->getRow();
            goto out;
            break;
        case 'kegiatanpg':
            $builder = $db->query("SELECT SUM(pg) vol FROM paket pkt WHERE pkt.kdprogram = '$kdprogram' AND pkt.kdgiat = '$kdgiat'  limit 1
                ")->getRow();
            goto out;
            break;
        case 'kegiatanrealisasi':
            $builder = $db->query("SELECT SUM($field1) vol FROM paket pkt WHERE 
            pkt.kdprogram = '$kdprogram' AND pkt.kdgiat = '$kdgiat'  limit 1
            ")->getRow();
            goto out;
            break;

        case 'kegiatankeu_rn':
            $builder = $db->query(
                "SELECT SUM($field1) / sum(pg) * 100 / 
                    (SELECT COUNT($field1) 
                    FROM paket
                    WHERE
                    kdprogram = '$kdprogram'
                    AND kdgiat = '$kdgiat' limit 1
                   
                
                    ) vol 
                    FROM paket pkt 
                    WHERE 
                pkt.kdprogram = '$kdprogram' 
                AND pkt.kdgiat = '$kdgiat'  limit 1
                  
                
                "
            )->getRow();
            goto out;
            break;

        case 'kegiatankeu_rl':
            $builder = $db->query(
                "SELECT SUM($field1) / sum(pg) * 100 /
                    (SELECT COUNT($field1) 
                    FROM paket
                    WHERE
                    kdprogram = '$kdprogram'
                    AND kdgiat = '$kdgiat' limit 1
                   
                
                    ) vol 
                    FROM paket pkt 
                    WHERE 
                    pkt.kdprogram = '$kdprogram' 
                    AND pkt.kdgiat = '$kdgiat' limit 1
                
                    "
            )->getRow();
            goto out;
            break;

        case 'kegiatanfis_rn':
            $builder = $db->query(
                "SELECT SUM($field1) /
                    (SELECT COUNT($field1) 
                    FROM paket
                    WHERE
                    kdprogram = '$kdprogram'
                    AND kdgiat = '$kdgiat' limit 1
                   
                
                    ) vol 
                    FROM paket pkt 
                    WHERE 
                    pkt.kdprogram = '$kdprogram' 
                    AND pkt.kdgiat = '$kdgiat' limit 1
                
                    "
            )->getRow();
            goto out;
            break;

        case 'kegiatanfis_rl':
            $builder = $db->query(
                "SELECT SUM($field1) / SUM(pg) * 100 /
                    (SELECT COUNT($field1) 
                    FROM paket
                    WHERE
                    kdprogram = '$kdprogram'
                    AND kdgiat = '$kdgiat' limit 1
                   
                
                    ) vol 
                    FROM paket pkt 
                    WHERE 
                pkt.kdprogram = '$kdprogram' 
                AND pkt.kdgiat = '$kdgiat'  limit 1
                  
                
                "
            )->getRow();
            goto out;
            break;

            //  program case
        case 'programvol':
            $builder = $db->query("SELECT SUM(REPLACE(pkt.vol,',','.')) vol FROM paket pkt WHERE 
            pkt.kdprogram = '$kdprogram' limit 1   
            ")->getRow();
            goto out;
            break;
        case 'programpg':
            $builder = $db->query("SELECT SUM(pg) vol FROM paket pkt WHERE pkt.kdprogram = '$kdprogram'  limit 1 
                ")->getRow();
            goto out;
            break;
        case 'programrealisasi':
            $builder = $db->query("SELECT SUM($field1) vol FROM paket pkt WHERE 
            pkt.kdprogram = '$kdprogram' limit 1
            ")->getRow();
            goto out;
            break;

        case 'programkeu_rn':
            $builder = $db->query(
                "SELECT SUM($field1) / sum(pg) * 100 AS vol 
                    FROM paket pkt 
                    WHERE 
                pkt.kdprogram = '$kdprogram' limit 1"
            )->getRow();

            goto out;
            break;

        case 'programkeu_rl':
            $builder = $db->query(
                "SELECT SUM($field1) / sum(pg) * 100 AS vol  
                    FROM paket pkt 
                    WHERE 
                    pkt.kdprogram = '$kdprogram' limit 1"
            )->getRow();
            goto out;
            break;

        case 'programfis_rn':
            $builder = $db->query(
                "SELECT SUM($field1) /
                    (SELECT COUNT($field1) 
                    FROM paket
                    WHERE
                    kdprogram = '$kdprogram'
                    ) AS vol 
                    FROM paket pkt 
                    WHERE 
                    pkt.kdprogram = '$kdprogram' limit 1
                    "
            )->getRow();
            goto out;
            break;

        case 'programfis_rl':
            $builder = $db->query(
                "SELECT SUM($field1) / SUM(pg) * 100  AS vol 
                    FROM paket pkt 
                    WHERE 
                pkt.kdprogram = '$kdprogram' limit 1
                  
                
                "
            )->getRow();
            goto out;
            break;

        default:
            # code...
            break;
    }

    out:
    $res = $builder->vol;
    //loading query builder
    return $res;
    // Produces: SELECT * FROM table_name
}

function unique_multidim_array($array, $key, $key1, $addedKey, $addedKey1, $addedKey2, $addedKey3, $addedKey4, $addedKey5, $addedKey6, $addedKey7, $addedKey8)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();
    $key1_array = array();
    // $key2_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array) && !in_array($val[$key1], $key1_array)) {
            $key_array[$i] = $val[$key];
            $key1_array[$i] = $val[$key1];
            // $key2_array[$i] = $val[$key2];
            $temp_array[$i] = $val;
        } else {
            $pkey = array_search($val[$key], $key_array);
            $pkey1 = array_search($val[$key1], $key1_array);
            // $pkey2 = array_search($val[$key2], $key2_array);
            $count = array_count_values(array_column($array, $key));
            // print_r($count[$val[$key]]);exit;

            if ($pkey == $pkey1) {
                $temp_array[$pkey][$addedKey] += $val[$addedKey];
                $temp_array[$pkey][$addedKey1] += $val[$addedKey1];
                $temp_array[$pkey][$addedKey2] += $val[$addedKey2];
                $temp_array[$pkey][$addedKey3] += $val[$addedKey3];
                $temp_array[$pkey][$addedKey4] += $val[$addedKey4];
                $temp_array[$pkey][$addedKey5] += $val[$addedKey5];
                $temp_array[$pkey][$addedKey6] += $val[$addedKey6];
                $temp_array[$pkey][$addedKey8] += $val[$addedKey8];
                $temp_array[$pkey][$addedKey7] = $count[$val[$key]];
            } else {
                $key_array[$i] = $val[$key];
                $key1_array[$i] = $val[$key1];
                // $key2_array[$i] = $val[$key2];
                $temp_array[$i] = $val;
            }
            // die;
        }
        $i++;
    }
    return $temp_array;
}


function getoutputname($db, $kdprogram, $kdgiat, $kdoutput, $kdsoutput)
{
    $db      = \Config\Database::connect($db);
    $builder = $db->query("SELECT ursoutput FROM d_soutput WHERE 
        kdprogram = '$kdprogram' AND kdgiat = '$kdgiat' AND kdoutput = '$kdoutput' 
         AND kdsoutput = '$kdsoutput' limit 1")->getRow();
    return $builder->ursoutput;
}
