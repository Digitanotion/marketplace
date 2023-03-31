<div class="card-body">
                <table id="categoriesList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Count</th>
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Options</th>
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $getCategoryAll=$adManager->getAllMallCategory();
                    if ($getCategoryAll['status']=="1"){
                      $count=1;
                    foreach ($getCategoryAll['message'] as  $value) {
                      //Get Category Details of Parent
                      $getParentCateg=$adManager->getCategByID($value['mallCategParent']);
                    ?>  
                  <tr>
                    <td><?php echo $count;?></td>
                    <td><a href="<?php echo "?lid=".$secManager->CSRFToken."&clid=".$value['mallCategID']."&cat_focus=1&cp=".$value['mallCategParent']."&push=edcateg&category=".$value['mallCategName'];?>"><?php echo $value['mallCategName']; ?></a>
                    </td>
                    <td><?php echo ($getParentCateg['status']=="404")? "No Parent" : "<a href='#'>".$getParentCateg['message'][0]['mallCategName']; ?></a></td>
                    <th><?php //Get category Options table ?></th>
                    <td><img src="/sdl"></td>
                    <td><a href="<?php echo "?lid=".$secManager->CSRFToken."&clid=".$value['mallCategID']."&cat_focus=1&cp=".$value['mallCategParent']."&delCateg=true&push=addcateg&category=".$value['mallCategName'];?>" class=""><i class="fa fa-trash"></i></a></td>
                  </tr>
                      <?php $count++; }}?>
                  </tbody>
                </table>
              </div>