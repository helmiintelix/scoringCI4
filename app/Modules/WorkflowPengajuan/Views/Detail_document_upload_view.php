<?php

    use App\Models\Common_model;
    $this->Common_model = new Common_model();

?>
<div class="row">
    <div class="col-sm-12 table-responsive">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" style="text-align: center;">#</th>
                    <th scope="col" style="text-align: center;">FILE</th>
                    <th scope="col" style="text-align: center;">FILE NAME</th>
                    <th scope="col" style="text-align: center;">TIPE DOCUMENT</th>
                    <th scope="col" style="text-align: center;">UPLOAD TIME</th>
                    <th scope="col" style="text-align: center;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $i=1;
                    foreach ($history_upload as $key => $value) {
                        
                        // $path = explode("/",$value['path']);
                        $str_path= ltrim($value['path'], './');
                        // foreach ($path as $zz => $xx) {
                        //     if( $zz>4){
                        //         $str_path .= '/'.$xx;
                        //     }
                            
                        // }

                        $ext = str_replace('.','',$value['ext']);
                        $html_file='';
                        $html_action_view='';
                        if($ext=='jpg'|| $ext=='png' || $ext=='jpeg' || $ext=='gif'){
                            $html_file = '<center><img src="'.base_url().$str_path.'" style="max-width:100px;height:70px"></img></center>';
                            $html_action_view = '<a href="#" data-name="'.$value['file_name'].'" data-src="'.base_url().$str_path.'" onClick="showImage(this)">'.
                                                    '<i class="bi bi-eye"></i>'.
                                                '</a>';
                        }else{
                            $html_file = '<i class="bi bi-file-earmark"></i>';
                        }


                        

                        $html = '<tr>'.
                                    '<th scope="row" style="text-align: center;">'.$i.'</th>'.
                                    '<td style="text-align: center;">'.$html_file.'</td>'.
                                    '<td style="text-align: center;">'.$value['file_name'].'</td>'.
                                    '<td style="text-align: center;">'.$ext.'</td>'.
                                    '<td style="text-align: center;">'.$this->Common_model->MonthIndo($value['created_time']).'</td>'.
                                    '<td style="text-align: center;">
                                        '. $html_action_view .'
                                        <a href="'.base_url().$str_path.'" download style="margin-left: 15px;">
                                         <i class="bi bi-download"></i>
                                        </a>
                                    </td>'.
                                '</tr>';
                        echo $html;
                        $i++;
                    }
                ?>


            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    function showImage(elm) {
        let elms = $(elm);
        let href = elms.attr('data-src');
        let name = elms.attr('data-name');

        swal({
            title: "IMAGE",
            text: name,
            icon: href,
            html: true
        });
    }
</script>