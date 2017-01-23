<?php
defined('BASEPATH') OR exit('No direct script access allowed');
setlocale(LC_TIME, 'ind');
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pengumuman Jadwal Kuliah</title>
        <style>
            .printable {
                width: 800px;
            }
            h1 {
                text-align: center;
                text-transform: uppercase;
                font-size: 72px;
                text-decoration: underline;
                letter-spacing: 14px;
                margin-top: 50px;
                margin-bottom: 0px;
            }
            h2 {
                text-align: center;
                font-size: 48px;
                margin: 0px;
            }
            table {
                width: 100%;
                font-size: 24px;
            }
            table.detail {
                margin-top: 30px;
            }
            table.detail td {
                padding-top: 20px;
            }
            table.detail td[colspan="3"] {
                text-align: center;
                font-style: italic;
            }
            table.detail td:nth-child(3) {
                border-bottom: black dotted
            }
            table.signature {
                margin-top: 40px;
            }
            table.signature td {
                width: 33%;
            }
        </style>
    </head>
    <body>
        <div class="printable">
            <h1>Pengumuman</h1>
            <h2>Jadwal Kuliah</h2>
            <table class="detail">
                <tr>
                    <td>Mata Kuliah</td>
                    <td>:</td>
                    <td><?= $perubahan->mataKuliahName ?></td>
                </tr>
                <tr>
                    <td>Kode MK</td>
                    <td>:</td>
                    <td><?= $perubahan->mataKuliahCode ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td><?= $perubahan->class ?></td>
                </tr>
                <tr>
                    <td colspan="3"><?= $perubahan->changeType === 'T' ? 'ditambahkan' : 'semula' ?></td>
                </tr>
                <?php if ($perubahan->changeType !== 'T'): ?>                
                    <tr>
                        <td>Hari</td>
                        <td>:</td>
                        <td><?= $perubahan->fromDateTime === null ? '' : strftime('%A, %#d %B %Y', $perubahan->fromDateTime) ?></td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>:</td>
                        <td><?= $perubahan->fromDateTime === null ? '' : strftime('%H:%M', $perubahan->fromDateTime) ?></td>
                    </tr>
                    <tr>
                        <td>Ruang</td>
                        <td>:</td>
                        <td><?= $perubahan->fromRoom ?></td>
                    </tr>
                <?php endif; ?>                
                <tr>
                    <td colspan="3"><?= $perubahan->changeType === 'X' ? 'ditiadakan' : 'menjadi' ?></td>
                </tr>
                <?php if ($perubahan->changeType !== 'X'): ?>
                    <tr>
                        <td>Hari</td>
                        <td>:</td>
                        <td><?= $perubahan->toDateTime === null ? '' : strftime('%A, %#d %B %Y', $perubahan->toDateTime) ?></td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>:</td>
                        <td><?= $perubahan->toDateTime === null ? '' : strftime('%H:%M', $perubahan->toDateTime) ?></td>
                    </tr>
                    <tr>
                        <td>Ruang</td>
                        <td>:</td>
                        <td><?= $perubahan->toRoom ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td><?= $perubahan->remarks ?></td>
                </tr>
            </table>
            <table class="signature">
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        Bandung, <?= strftime('%#d-%b-%Y') ?><br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        Administrasi FTIS
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>