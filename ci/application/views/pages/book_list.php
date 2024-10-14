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
                        <li><a href="/book">Book List</a></li>
                        <li><a href="/rental_status">Rental Status</a></li>
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
                <div class="list">
                    <table>
                        <thead>
                            <tr>
                                <th class="num">No</th>
                                <th class="book_name">책 제목</th>
                                <th class="author">저자</th>
                                <th class="publisher">출판사</th>
                                <th class="status">상태</th>
                                <th class="borrow">대출-예약</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($books as $book) :?>
                                <tr>
                                    <td><?=$book->seq?></td>
                                    <td><?=$book->book_name?></td>
                                    <td><?=$book->author?></td>
                                    <td><?=$book->publisher?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <!-- 페이징 링크 표시 -->
                <div class="pagination">
                    <?php if($current_page > 1):?>
                        <a href="<?php echo site_url('book/' . ($current_page - 1)); ?>"><</a>
                    <?php endif?>
                    <?php
                        // 10개의 페이지 링크를 생성
                        $start_page = max(1, $current_page - 5); // 현재 페이지 기준으로 시작 페이지
                        $end_page = min($total_pages, $start_page + 9); // 끝 페이지 (최대 10개)

                        // 시작 페이지를 1로 설정하기 위해 조정
                        if ($end_page - $start_page < 9) {
                            $start_page = max(1, $end_page - 9);
                        }

                        for ($i = $start_page; $i <= $end_page; $i++): 
                    ?>
                    <a href="<?php echo site_url('book/' . $i); ?>" 
                        <?php echo ($i == $current_page) ? 'class="active"' : ''; ?>>
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                   

                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo site_url('book/' . ($current_page + 1)); ?>">></a>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <!-- content -->
    </div>

    


    <!-- content -->
    <!-- content -->


</body>
</html>