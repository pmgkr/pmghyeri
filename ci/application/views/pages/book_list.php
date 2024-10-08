<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>

    <!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/asset/css/common.css" />
	<link rel="stylesheet" type="text/css" href="/asset/css/book.css" >
</head>
<body>
    <!-- Welcome to the show -->
    <div class="book">

        <!-- header -->
        <header>
            <div class="wrap">
                <div class="left">
                    <h1 class="logo">
                        <a class="navbar-brand" href="/">
                            <img src="/asset/images/logo.png" alt="PMG Group">
                        </a>
                    </h1>
                    <ul>
                        <li><a href="">Book List</a></li>
                        <li><a href="/home">Rental Status</a></li>
                        <li><a href="">Wish Book</a></li>
                    </ul>
                </div>
                <div class="right">
                    <a href="/">
                        <div class="profile_img">
                            <img src="/asset/images/profile.jpg" alt="profile">
                        </div>
                        <span class="profile_name">홍길동</span>
                    </a>
                </div>
            </div>
        </header>
        <!-- header -->

        <!-- content -->
        <div class="content">
            <section class="section">
                <table>
                    <thead>
                        <tr>
                            <th>책 제목</th>
                            <th>출판사</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($book_list as $books) :?>
                            <tr>
                                <td><?=$books->book_name?></td>
                                <td><?=$books->publisher?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </section>
        </div>
        <!-- content -->
    </div>

    


    <!-- content -->
    <!-- content -->


</body>
</html>