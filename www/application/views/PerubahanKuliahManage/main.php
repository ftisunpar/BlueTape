<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>
        <br>
        <div class="container">
            <div class="card">
                <div class="card-header" data-toggle="collapse" data-target="#statistikPerubahanKuliah">   
                    <div class="row">
                        <div class = "col">                 
                            Statistik Perubahan Kuliah
                        </div>
                        <div class= "col">
                            <a class ="float-right">
                                <i class="fas fa-angle-double-down" id ="collapseAccordion" style="color:black;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="collapse" id = "statistikPerubahanKuliah">
                    <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#" id="byYear">Statistik Berdasarkan Tahun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#" id="byDay">Statistik Berdasarkan Hari</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#">Statistik Berdasarkan Jam</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active text-center">                            
                                <canvas id="chartStatistic" style="width:100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    Permohonan Perubahan Kuliah
                </div>
                <br>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Permohonan</th>
                            <th scope="col">Kode MK</th>
                            <th scope="col">Perubahan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <th>#<?= $request->id ?></th>
                                <td><span class="badge badge-<?= $request->labelClass ?>"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                <td><?= $request->mataKuliahCode ?></td>
                                <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#detail<?= $request->id ?>" id="detailIkon<?= $request->id ?>"><i class="fas fa-eye blueiconcolor"></i></a>
                                    <a target="_blank" href="/PerubahanKuliahManage/printview/<?= $request->id ?>"><i class="fas fa-print"></i></a>
                                    <a data-toggle="modal" data-target="#konfirmasi<?= $request->id ?>"><i class="fas fa-thumbs-up"></i></a>
                                    <a data-toggle="modal" data-target="#tolak<?= $request->id ?>"><i class="fas fa-thumbs-down"></i></a>
                                    <a data-toggle="modal" data-target="#hapus<?= $request->id ?>"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($numOfPages > 1): ?>
                    <ul class="pagination justify-content-center" role="navigation" aria-label="Pagination">
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i === $page): ?>
                                <li class="current page-item active"><span class="page-link"><?= $i ?></span></li>
                            <?php else: ?>
                                <li class = "page-item"><a href="?page=<?= $i ?>"  aria-label="Halaman <?= $i ?>" class="page-link"><?= $i ?></a></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ul>
                <?php endif; ?>
                <?php foreach ($requests as $request): ?>
                    <div class="modal fade" id="detail<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Detail Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th>E-mail Pemohon</th>
                                            <td><?= $request->requestByEmail ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pemohon</th>
                                            <td><?= $request->requestByName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Permohonan</th>
                                            <td><?= $request->requestDateTime ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kode Mata Kuliah</th>
                                            <td><?= $request->mataKuliahCode ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Mata Kuliah</th>
                                            <td><?= $request->mataKuliahName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kelas</th>
                                            <td><?= $request->class ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Perubahan</th>
                                            <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Dari Hari/Jam</th>
                                            <td><time datetime="<?= $request->fromDateTime ?>"><?= $request->fromDateTime ?></time></td>
                                        </tr>
                                        <tr>
                                            <th>Dari Ruang</th>
                                            <td><?= $request->fromRoom ?></td>
                                        </tr>
                                        <?php foreach (json_decode($request->to) as $to ): ?>
                                            <tr>
                                                <th>Menjadi Hari/Jam</th>
                                                <td><time datetime="<?= $to->dateTime ?>"><?= $to->dateTime ?></time>
                                                <?= empty($to->toTimeFinish)? '': '- <time datetime="'.$to->toTimeFinish.'">'.$to->toTimeFinish.'</time>'?></td>
                                            </tr>
                                            <tr>
                                                <th>Menjadi Ruang</th>
                                                <td><?= $to->room ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td><?= $request->remarks ?></td>
                                        </tr>
                                        <tr>
                                            <th>E-mail Penjawab</th>
                                            <td><?= $request->answeredByEmail ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Dijawab</th>
                                            <td><?= $request->answeredDateTime ?></td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan Penjawab</th>
                                            <td><?= $request->answeredMessage ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="konfirmasi<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Konfirmasi Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/answer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="confirmed"/>
                                        <div class="form-group">
                                            <label>Email penjawab:</label>
                                            <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan:</label>
                                            <input class="form-control" name="answeredMessage" class="input-group-field" type="text"/>
                                        </div>
                                        <p>&nbsp;</p>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Konfirmasi"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="tolak<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Tolak Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/answer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="rejected"/>
                                        <div class="form-group">
                                            <label>Email penjawab:</label>
                                            <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Alasan penolakan:</label>
                                            <input class="form-control" name="answeredMessage" class="input-group-field" type="text" required/>
                                        </div>
                                        <p>&nbsp;</p>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-danger" value="Tolak"/>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="hapus<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Hapus Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/remove">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="remove"/>
                                        <p><strong>Yakin ingin menghapus?</strong></p>
                                        <p>Data akan hilang selamanya dari catatan. Biasanya menghapus tidak diperlukan, cukup menolak atau mencetak.</p>
                                        <input type="submit" class="btn btn-danger" value="Hapus"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php $this->load->view('templates/script_foundation'); ?>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                var perubahanKuliahChart;                
                var canvascontainer = $('#chartStatistic');
                var context = canvascontainer[0].getContext('2d');   
                var chartType='bar';
                <?php                     
                    $yearLabel='';
                    $dayLabel='';
                    $hourLabel='';
                    $diganti=array_fill(0,3,'');
                    $tambahan=array_fill(0,3,'');
                    $ditiadakan=array_fill(0,3,'');             

                    foreach($statistic->requestByYear as $key => $row){                        
                        $yearLabel .= '"'.$key.'",';         
                        $diganti[0] .='"0",';
                        $ditiadakan[0] .='"0",';
                        $tambahan[0] .= '"0",';

                        foreach($row as $rowData){
                            $perubahan = strtolower(PerubahanKuliah_model::CHANGETYPE_TYPES[$rowData->changeType]);
                            $$perubahan[0] = substr($$perubahan[0],0,strlen($$perubahan[0])-4).'"'.$rowData->count.'",';                    
                        }                        
                    }
                    foreach($statistic->requestByDay as $key => $row){                            
                        $dayLabel .= '"'.$key.'",';         
                        $diganti[1] .='"0",';
                        $ditiadakan[1] .='"0",';
                        $tambahan[1] .= '"0",';
                        
                        foreach($row as $rowData){
                            $perubahan = strtolower(PerubahanKuliah_model::CHANGETYPE_TYPES[$rowData->changeType]);
                            $$perubahan[1] = substr($$perubahan[1],0,strlen($$perubahan[1])-4).'"'.$rowData->count.'",';
                        }
                    }
                    foreach($statistic->requestByHour as $key => $row){                            
                        $hourLabel .= '"'.$key.'",';         
                        $diganti[2] .='"0",';
                        $ditiadakan[2] .='"0",';
                        $tambahan[2] .= '"0",';
                        
                        foreach($row as $rowData){
                            $perubahan = strtolower(PerubahanKuliah_model::CHANGETYPE_TYPES[$rowData->changeType]);
                            $$perubahan[2] = substr($$perubahan[2],0,strlen($$perubahan[2])-4).'"'.$rowData->count.'",';
                        }
                    }
                ?>
                function fillDataByYear(){
                    var chartData = 
                    {        
                        labels: [<?=substr($yearLabel,0,strlen($yearLabel)-1);?>],                                          
                        datasets: [{
                            label: 'Diganti',
                            data: [<?=substr($diganti[0],0,strlen($diganti[0])-1);?>],
                            backgroundColor:'rgba(68, 114, 196, 0.5)',
                            borderWidth: 1                      
                        },
                        {
                            label: 'Ditiadakan',
                            data: [<?=substr($ditiadakan[0],0,strlen($ditiadakan[0])-1);?>],
                            backgroundColor:'rgba(237, 125, 49, 0.5)',
                            borderWidth: 1
                        },
                        {
                            label: 'Tambahan',
                            data: [<?=substr($tambahan[0],0,strlen($tambahan[0])-1);?>],
                            backgroundColor:'rgba(165, 165, 165, 0.3)',
                            borderWidth: 1
                        }]
                    }
                    return chartData;
                }
                function fillDataByDay(){
                    var chartData = 
                    {            
                        labels: [<?=substr($dayLabel,0,strlen($dayLabel)-1);?>],                                    
                        datasets: [{
                            label: 'Diganti',
                            data: [<?=substr($diganti[1],0,strlen($diganti[1])-1);?>],
                            backgroundColor:'rgba(68, 114, 196, 0.5)',
                            borderWidth: 1                      
                        },
                        {
                            label: 'Ditiadakan',
                            data: [<?=substr($ditiadakan[1],0,strlen($ditiadakan[1])-1);?>],
                            backgroundColor:'rgba(237, 125, 49, 0.5)',
                            borderWidth: 1
                        },
                        {
                            label: 'Tambahan',
                            data: [<?=substr($tambahan[1],0,strlen($tambahan[1])-1);?>],
                            backgroundColor:'rgba(165, 165, 165, 0.3)',
                            borderWidth: 1
                        }]
                    };
                    return chartData;
                }
                function fillDataByHour(){
                    var chartData = 
                    {            
                        labels: [<?=substr($hourLabel,0,strlen($hourLabel)-1);?>],                                    
                        datasets: [{
                            label: 'Diganti',
                            data: [<?=substr($diganti[2],0,strlen($diganti[2])-1);?>],
                            backgroundColor:'rgba(68, 114, 196, 1)',
                            borderColor:'rgba(68, 114, 196, 0.6)',
                            fill:false,
                            borderWidth: 3,
                            pointStyle:'line',    
                            pointRadius:0,
                            lineTension:0  
                        },
                        {
                            label: 'Ditiadakan',
                            data: [<?=substr($ditiadakan[2],0,strlen($ditiadakan[2])-1);?>],
                            backgroundColor:'rgba(237, 125, 49, 1)',
                            borderColor:'rgba(237, 125, 49, 0.6)',
                            fill:false,
                            borderWidth: 3,
                            pointStyle:'line',    
                            pointRadius:0,
                            lineTension:0      
                        },
                        {
                            label: 'Tambahan',
                            data: [<?=substr($tambahan[2],0,strlen($tambahan[2])-1);?>],
                            backgroundColor:'rgba(165, 165, 165, 1)',
                            borderColor: 'rgba(165, 165, 165, 0.6)',
                            fill:false,
                            borderWidth: 3,
                            pointStyle:'line',    
                            pointRadius:0,
                            lineTension:0    
                        }]
                    };
                    return chartData;
                }
                function makeChart(chartData,chartTitle,chartScales){
                    perubahanKuliahChart = new Chart(context, {
                        type: chartType,
                        data: chartData,
                        options:{
                            title:{
                                display:true,
                                fontSize:24,
                                fontColor:"black",
                                text: chartTitle
                            },
                            tooltips:{
                                mode:'index',
                                intersect:false
                            },
                            legend:{       
                                labels:{                                
                                    usePointStyle:chartType==='line',
                                    fontSize:16                                  
                                }
                            },
                            scales:chartScales                            
                        }                               
                    });           
                }

                $('#statistikPerubahanKuliah a').on('click',function(e){
                    e.preventDefault()
                    if($(this).attr('id') === 'byDay'){      
                        if(chartType === 'bar'){
                            perubahanKuliahChart.data = fillDataByDay();
                            perubahanKuliahChart.options.title.text = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';                        
                            perubahanKuliahChart.options.scales.xAxes[0].scaleLabel.labelString = 'Hari';
                            perubahanKuliahChart.update();
                        }
                        else{
                            perubahanKuliahChart.destroy();
                            chartType='bar';
                            chartData = fillDataByDay();
                            chartTitle = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                            chartScales = {
                                xAxes:[{
                                    stacked:true,
                                    scaleLabel:{
                                        display:true,
                                        labelString:'Hari'
                                    }
                                }],
                                yAxes:[{
                                    ticks:{
                                        beginAtZero:true,
                                        precision:0
                                    },
                                    stacked:true
                                }]
                            };
                            makeChart(chartData,chartTitle,chartScales);
                        }
                    }
                    else if($(this).attr('id')==='byYear'){ 
                        if(chartType ==='bar'){     
                            perubahanKuliahChart.data = fillDataByYear();
                            perubahanKuliahChart.options.title.text = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                            perubahanKuliahChart.options.scales.xAxes[0].scaleLabel.labelString = 'Tahun';
                            perubahanKuliahChart.update();
                        }
                        else{
                            chartType='bar';
                            perubahanKuliahChart.destroy();
                            chartData = fillDataByYear();
                            chartTitle = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                            chartScales =
                            {
                                xAxes:[{
                                    stacked:true,
                                    scaleLabel:{
                                        display:true,
                                        labelString:'Tahun'
                                    }
                                }],
                                yAxes:[{
                                    ticks:{
                                        beginAtZero:true,
                                        precision:0
                                    },
                                    stacked:true
                                }]
                            };
                            makeChart(chartData,chartTitle,chartScales);
                        }
                    }
                    else{
                        perubahanKuliahChart.destroy();
                        chartType = 'line'
                        chartData = fillDataByHour();
                        chartTitle = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';                        
                        chartScales = {
                            xAxes:[{
                                scaleLabel:{
                                    display:true,
                                    labelString:'Jam'
                                }
                            }],
                            yAxes:[{
                                ticks:{
                                    beginAtZero:true,
                                    precision:0
                                }
                            }]
                        };
                        makeChart(chartData,chartTitle,chartScales);
                    }
                });  
                           

                $('#statistikPerubahanKuliah').on('shown.bs.collapse',function(){                                        
                    var chartData='';
                    var chartTitle= '';                    
                    var chartScales = 
                    {
                        xAxes:[{
                            stacked:true,
                            scaleLabel:{
                                display:true,
                                labelString:'Tahun'
                            }
                        }],
                        yAxes:[{
                            ticks:{
                                beginAtZero:true,
                                precision:0
                            },
                            stacked:true
                        }]
                    };
                    if($(this).find('a.active').attr('id')==='byYear'){            
                        chartTitle='<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                        chartType='bar';
                        chartData = fillDataByYear();
                    }
                    else if($(this).find('a.active').attr('id')=='byDay'){
                        chartTitle = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                        chartScales.xAxes[0].scaleLabel.labelString = 'Hari';
                        chartType='bar';
                        chartData = fillDataByDay();
                    }
                    else{
                        chartType = 'line';
                        chartTitle = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                        chartData = fillDataByHour();
                        chartScales = {
                            xAxes:[{
                                scaleLabel:{
                                    display:true,
                                    labelString:'Jam'
                                }
                            }],
                            yAxes:[{
                                ticks:{
                                    beginAtZero:true,
                                    precision:0
                                }
                            }]
                        };
                    }
                    $('#collapseAccordion').removeClass('fas fa-angle-double-down').
                            addClass('fas fa-angle-double-up');
                    makeChart(chartData,chartTitle,chartScales);                             
                });    
                
                $('#statistikPerubahanKuliah').on('hidden.bs.collapse',function(){
                    $('#collapseAccordion').removeClass('fas fa-angle-double-up')
                            .addClass('fas fa-angle-double-down');
                    perubahanKuliahChart.destroy();
                });            
            });
        
        </script>
    </body>
</html>