<?php
$cattablist = Category::getInfoByParentid($tabcatid);
if(count($cattablist) > 1){ ?>
    <div id="tablist">
        <ul>
            <?php
            foreach ($cattablist as $tabchild){
                $tabmodelinfo = Products_model::getInfoByCategory($tabchild['id']);
                $prolisturl = str_replace("attr","list", $tabmodelinfo['attrname']).".php";
                $active = '';
                if($tabmodelid == $tabmodelinfo['id'])
                    $active = 'class="active"';
                echo '<li '.$active.'><a href="'.$prolisturl.'">'.$tabchild['name'].'</a></li>';
            }
            ?>
        </ul>
    </div>
<?php } ?>