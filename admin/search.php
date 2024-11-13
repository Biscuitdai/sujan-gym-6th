<?php
include('layout/header.php');
include('layout/left.php');
include('layout/adminsession.php');
?>

<div id="right">
    <link rel="stylesheet" href="../css/tableDecorate.css">

    <form action="memberlist.php" method="post">
        <input type="search" name="text_search" class="centermember_botton" placeholder="Enter a name" value="">
        <input type="submit" name="search" value="search" class="centermember_botton">
    </form>

    <table class="membership">
        <tr>
            <td colspan="8">
                <h1 class="center">MEMBER LIST</h1>
            </td>
        </tr>
        <tr>
            <th width=5%>SN</th>
            <th width=20%>Name</th>
            <th width=10%>Phone</th>
            <th width=25%>Email</th>
            <th width=5%>Status</th>
            <th width=15%>Date of Join</th>
            <th width=5%>Profile</th>
            <th width=15%>Action</th>
        </tr>
        <?php
        if (isset($_POST['search'])) {
            $text_search = $_POST['text_search'];
            $conn = new mysqli("localhost", "root", "", "gsms");
            if ($conn->connect_error) {
                die("Database connection error");
            }

            $sql = "SELECT * FROM member ORDER BY name";
            $r = $conn->query($sql);

            $membersList = [];
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $membersList[] = $row;
                }
            }

            function ternary_search_product($left, $right, $arr, $search) {
                if ($left <= $right) {
                    $mid1 = $left + floor(($right - $left) / 3);
                    $mid2 = $right - floor(($right - $left) / 3);

                    $name_mid1 = $arr[$mid1]["name"];
                    $name_mid2 = $arr[$mid2]["name"];

                    if ($name_mid1 === $search) {
                        return $arr[$mid1];
                    } elseif ($name_mid2 === $search) {
                        return $arr[$mid2];
                    }

                    if ($search < $name_mid1) {
                        return ternary_search_product($left, $mid1 - 1, $arr, $search);
                    } elseif ($search > $name_mid2) {
                        return ternary_search_product($mid2 + 1, $right, $arr, $search);
                    } else {
                        return ternary_search_product($mid1 + 1, $mid2 - 1, $arr, $search);
                    }
                }
                return null;
            }

            $result = ternary_search_product(0, count($membersList) - 1, $membersList, $text_search);
            if ($result !== null) {
                echo"<tr> <td> </td></tr>";
                echo "<tr>
                     <td>1</td>
                     <td>" . $result["name"] . "</td>
                     <td>" . $result["phone"] . "</td>
                     <td>" . $result["email"] . "</td>
                     <td>" . $result["status"] . "</td>
                     <td>" . $result["date"] . "</td>
                     <td><img src='../img/" . $result["image"] . "' id='img_id'></td>
                     <td class='h-center'>
                         <form action='edit_member_list.php' method='get'>
                             <input type='hidden' value='" . $result["mid"] . "' name='member_id' />
                             <input type='submit' name='edit_member' value='Edit' class='edit_delete_green'>
                         </form>
                         <form action='memberlist.php' method='post'>
                             <input type='hidden' value='" . $result["mid"] . "' name='member_id' />
                             <input type='submit' name='delete_member' value='Delete' class='edit_delete_red'>
                         </form>
                     </td>
                 </tr>";
            } else {
                echo "<tr> <td colspan=8>No result found</td> </tr>";
            }
        }
        ?>
    </table>
</div>
<script src="../js/js.js"></script>

<?php
include('layout/footer.php');
?>
