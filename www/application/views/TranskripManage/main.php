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
                <div class="card-header" data-toggle="collapse" data-target="#statistikTranskrip">   
                    <div class="row">
                        <div class = "col">                 
                            Statistik Transkrip 
                        </div>
                        <div class= "col">
                            <a class ="float-right" >
                                <i class="fas fa-angle-double-down" id ="collapseAccordion" style="color:black;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="collapse" id = "statistikTranskrip">
                    <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#" id="byYear">Statistik Berdasarkan Tahun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#" id="byDay">Statistik Berdasarkan Hari</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="byHour">Statistik Berdasarkan Jam</a>
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
                        Permintaan Transkrip
                    </div>
                    <div class="card-body table-responsive">
                        <form method="GET" action="/TranskripManage">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cari NPM:</span>
                                </div>
                                <input name="npm" class="form-control" type="text" placeholder="2013730013" maxlength="10" minlength="10"<?= $npmQuery === NULL ? '' : " value='$npmQuery'" ?>/>
                                <div class="input-group-append">
                                    <input class="btn btn-outline-primary" type="submit" value="Cari"/>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal Permohonan</th>
                                <th scope="col">Tipe Transkrip</th>
                                <th scope="col">NPM</th>
                                <th scope="col">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <th>#<?= $request->id ?></th>
                                    <td><span class="badge badge-<?= $request->labelClass ?>"><?= $request->status ?></span></td>
                                    <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                    <td><?= $request->requestType ?></td>
                                    <td><?= isset($request->requestByNPM) ? $request->requestByNPM : '-' ?></td>
                                    <td>

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
                                                                <th>Tipe Transkrip</th>
                                                                <td><?= $request->requestType ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Keperluan</th>
                                                                <td><?= $request->requestUsage ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Jawaban</th>
                                                                <td><?= $request->answer ?></td>
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
                                        <a data-toggle="modal" data-target="#detail<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>

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
                                                        <form method="POST" action="/TranskripManage/answer">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                                            <input type="hidden" name="answer" value="rejected"/>
                                                            <div class="form-group">
                                                                <label>Email penjawab:</label>
                                                                <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alasan penolakan:</label>
                                                                <input class="form-control" name="answeredMessage" type="text" required/>
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
                                        <a data-toggle="modal" data-target="#tolak<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-thumbs-down"></i>
                                        </a>

                                        <div class="modal fade" id="cetak<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Cetak Permohonan #<?= $request->id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php if ($request->requestByNPM !== NULL): ?>
                                                            <a target="_blank" href="<?= sprintf($transkripURLs[$request->requestType], $request->requestByNPM) ?>">Klik untuk membuka DPS/LHS</a>
                                                        <?php else: ?>
                                                            Link DPS tidak tersedia
                                                        <?php endif ?>
                                                        <form method="POST" action="/TranskripManage/answer">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                                            <input type="hidden" name="answer" value="printed"/>
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <div class="form-group">
                                                                <label class="col-form-label">Email penjawab:</label>
                                                                <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label">Keterangan tambahan:</label>
                                                                <input class="form-control" name="answeredMessage" type="text" required/>
                                                            </div>
                                                            <p>&nbsp;</p>
                                                            <div class="form-group">
                                                                <input class="btn btn-primary" type="submit" class="button" value="Sudah dicetak"/>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#cetak<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-print"></i>
                                        </a>

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
                                                        <form method="POST" action="/TranskripManage/remove">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                                            <input type="hidden" name="answer" value="remove"/>
                                                            <p><strong>Yakin ingin menghapus?</strong></p>
                                                            <p>Data akan hilang selamanya dari catatan. Biasanya menghapus tidak diperlukan, cukup menolak atau mencetak.</p>
                                                            <input class="btn btn-danger" type="submit" value="Hapus"/>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#hapus<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
                </div>
            
        </div>

        <?php $this->load->view('templates/script_foundation'); ?>
        <script>
            $(document).ready(function () {
                var transkripChart;                
                var canvascontainer = $('#chartStatistic');
                var context = canvascontainer[0].getContext('2d');   
                var chartType='bar';
                <?php                     
                    $yearLabel='';
                    $dayLabel='';
                    $hourLabel='';
                    $rejected=array_fill(0,3,'');
                    $printed=array_fill(0,3,'');        

                    foreach($statistic->requestByYear as $key => $row){                        
                        $yearLabel .= '"'.$key.'",';         
                        $rejected[0] .='"0",';
                        $printed[0] .='"0",';

                        foreach($row as $rowData){
                            $perubahan = $rowData->answer;
                            $$perubahan[0] = substr($$perubahan[0],0,strlen($$perubahan[0])-4).'"'.$rowData->count.'",';                    
                        }                        
                    }
                    foreach($statistic->requestByDay as $key => $row){                            
                        $dayLabel .= '"'.$key.'",';         
                        $rejected[1] .='"0",';
                        $printed[1] .='"0",';                        
                        
                        foreach($row as $rowData){
                            $perubahan = $rowData->answer;
                            $$perubahan[1] = substr($$perubahan[1],0,strlen($$perubahan[1])-4).'"'.$rowData->count.'",';
                        }
                    }
                    foreach($statistic->requestByHour as $key => $row){                            
                        $hourLabel .= '"'.$key.'",';         
                        $rejected[2] .='"0",';
                        $printed[2] .='"0",';                        
                        
                        foreach($row as $rowData){
                            $perubahan =$rowData->answer;
                            $$perubahan[2] = substr($$perubahan[2],0,strlen($$perubahan[2])-4).'"'.$rowData->count.'",';
                        }
                    }
                ?>
                function fillDataByYear(){
                    var chartData = 
                    {                                                                                                                                                            
                        labels: [<?=substr($yearLabel,0,strlen($yearLabel)-1);?>],                                          
                        datasets: [{                                                    
                            label: 'Tercetak',
                            data: [<?=substr($printed[0],0,strlen($printed[0])-1);?>],
                            backgroundColor:'rgba(68, 114, 196, 0.5)',
                            borderWidth: 1
                        },
                        {
                            label: 'Ditolak',
                            data: [<?=substr($rejected[0],0,strlen($rejected[0])-1);?>],
                            backgroundColor:'rgba(237, 125, 49, 0.5)',
                            borderWidth: 1                      
                        }]
                    };
                    return chartData;
                }
                function fillDataByDay(){
                    var chartData = 
                    {                                                                                                                                                            
                        labels: [<?=substr($dayLabel,0,strlen($dayLabel)-1);?>],                                          
                        datasets: [{                                                    
                            label: 'Tercetak',
                            data: [<?=substr($printed[1],0,strlen($printed[1])-1);?>],
                            backgroundColor:'rgba(68, 114, 196, 0.5)',
                            borderWidth: 1
                        },
                        {
                            label: 'Ditolak',
                            data: [<?=substr($rejected[1],0,strlen($rejected[1])-1);?>],
                            backgroundColor:'rgba(237, 125, 49, 0.5)',
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
                            label: 'Tercetak',
                            data: [<?=substr($printed[2],0,strlen($printed[2])-1);?>],
                            backgroundColor:'rgba(68, 114, 196, 1)',
                            borderColor:'rgba(68, 114, 196, 0.6)',
                            fill:false,
                            borderWidth: 3,
                            pointStyle:'line',
                            pointRadius:0,
                            lineTension:0
                        },
                        {
                            label: 'Ditolak',
                            data: [<?=substr($rejected[2],0,strlen($rejected[2])-1);?>],
                            backgroundColor:'rgba(237, 125, 49, 1)',
                            fill:false,
                            borderColor:'rgba(237, 125, 49, 0.6)',
                            borderWidth: 3,
                            pointStyle:'line',
                            pointRadius:0,
                            lineTension:0
                        }]
                    };
                    return chartData;
                }

                function makeChart(chartData,chartTitle,chartScales){
                    transkripChart = new Chart(context, {
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

                $('#statistikTranskrip a').on('click',function(e){
                    e.preventDefault()
                    if($(this).attr('id') === 'byDay'){      
                        if(chartType === 'bar'){
                            transkripChart.data = fillDataByDay();
                            transkripChart.options.title.text = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';                        
                            transkripChart.options.scales.xAxes[0].scaleLabel.labelString = 'Hari';
                            transkripChart.update();
                        }
                        else{
                            transkripChart.destroy();
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
                            transkripChart.data = fillDataByYear();
                            transkripChart.options.title.text = '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
                            transkripChart.options.scales.xAxes[0].scaleLabel.labelString = 'Tahun';
                            transkripChart.update();
                        }
                        else{
                            chartType='bar';
                            transkripChart.destroy();
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
                        transkripChart.destroy();
                        chartType = 'line'
                        chartData = fillDataByHour();
                        chartTitle = 'Statistik Tercetak, Ditolak Berdasarkan jam';                        
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

                $('#statistikTranskrip').on('shown.bs.collapse',function(){                                        
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
                        chartTitle= '<?=$statistic->startingYear." - ". $statistic->endYear ?>';
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
                
                $('#statistikTranskrip').on('hidden.bs.collapse',function(){
                    $('#collapseAccordion').removeClass('fas fa-angle-double-up')
                            .addClass('fas fa-angle-double-down');
                    transkripChart.destroy();
                });  
            });
        </script>
    </body>
</html>