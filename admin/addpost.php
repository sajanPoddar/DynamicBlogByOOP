﻿<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="grid_10">

    <div class="box round first grid">
        <h2>Add New Post</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $cat = $_POST['cat'];
            $body = $_POST['body'];
            $tags = $_POST['tags'];
            $author = $_POST['author'];
            $userid = $_POST['userid'];
//             $image=$_POST['image'];
            $title = mysqli_real_escape_string($db->link, $title);
            $cat = mysqli_real_escape_string($db->link, $cat);
            $body = mysqli_real_escape_string($db->link, $body);
            $tags = mysqli_real_escape_string($db->link, $tags);
            $author = mysqli_real_escape_string($db->link, $author);
            $userid = mysqli_real_escape_string($db->link, $userid);

            /* upload image */
            $permited = array('jpg', 'jpeg', 'png', 'gif');
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_temp = $_FILES['image']['tmp_name'];
            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            $uploaded_image = "upload/" . $unique_image;

            if ($title == '' || $cat == '' || $body == '' || $tags == '' || $author == '' || $file_name == '') {
                echo "<span class='error'> field must not be empty!!</span>";
            } elseif ($file_size > 1048567) {
                echo "<span class='error'>Image Size should be less then 1MB!</span>";
            } elseif (in_array($file_ext, $permited) === false) {
                echo "<span class='error'>You can upload only:-" . implode(', ', $permited) . "</span>";
            } else {
                move_uploaded_file($file_temp, $uploaded_image);
                $query = "INSERT INTO tbl_post(cat,title,body,image,author,tags,userid)VALUES('$cat','$title','$body','$uploaded_image','$author','$tags','$userid')";
                $inserted_rows = $db->insert($query);
                if ($inserted_rows) {
                    echo "<span class='success'>Data Inserted Successfully.</span>";
                } else {
                    echo "<span class='error'>Data Not Inserted !</span>";
                }
            }
        }
        ?>
        <div class="block">               
            <form action="" method="post" enctype="multipart/form-data">
                <table class="form">

                    <tr>
                        <td>
                            <label>Title</label>
                        </td>
                        <td>
                            <input type="text" name="title" placeholder="Enter Post Title..." class="medium" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Category</label>
                        </td>
                        <td>
                            <select id="select" name="cat">
                                <option>select category</option>
                                <?php
                                $query = "select * from tbl_category";
                                $category = $db->select($query);
                                if ($category) {
                                    while ($result = $category->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>


<!--                    <tr>
                        <td>
                            <label>Date Picker</label>
                        </td>
                        <td>
                            <input type="text" id="date-picker" />
                        </td>
                    </tr>-->
                    <tr>
                        <td>
                            <label>Upload Image</label>
                        </td>
                        <td>
                            <input type="file" name="image"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-top: 9px;">
                            <label>Content</label>
                        </td>
                        <td>
                            <textarea class="tinymce" name="body"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Tags</label>
                        </td>
                        <td>
                            <input type="text" name="tags" placeholder="Enter tags..." class="medium" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Author</label>
                        </td>
                        <td>
                            <input type="text" name="author" value="<?php echo Session::get('username'); ?>" class="medium" />
                            <input type="hidden" name="userid" value="<?php echo Session::get('userID'); ?>" class="medium" />
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="submit" Value="Save" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- Load TinyMCE -->
<script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        setupTinyMCE();
        setDatePicker('date-picker');
        $('input[type="checkbox"]').fancybutton();
        $('input[type="radio"]').fancybutton();
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        setupLeftMenu();
        setSidebarHeight();
    });
</script>
<!-- /TinyMCE -->
<style type="text/css">
    #tinymce{font-size:15px !important;}
</style>
<?php include 'inc/footer.php'; ?>