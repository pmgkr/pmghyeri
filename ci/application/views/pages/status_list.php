<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>

    <!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/asset/css/common.css" />
	<link rel="stylesheet" type="text/css" href="/asset/css/book.css?" >
</head>
<body>
    <!-- Welcome to the show -->
    <div class="book">

        <!-- header -->
        <header>
            <div class="wrap">
                <div class="left">
                    <h1 class="logo">
                        <a class="navbar-brand" href="/book">
                            <img src="/asset/images/logo.png" alt="PMG Group">
                        </a>
                    </h1>
                    <ul>
                        <li><a href="/book">Book List</a></li>
                        <li><a href="/status">Rental Status</a></li>
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
                <!-- 검색 폼 추가 -->
                <form action="/status" method="get" class="search_form">
                    <input type="text" name="search" placeholder="검색어를 입력하세요" value="<?= isset($search_query) ? htmlspecialchars($search_query) : '' ?>">
                    <button type="submit" class="search_btn">
                        <i class="ico_search"></i>
                    </button>
                </form>
                <div class="list">
                    <table>
                        <thead>
                            <tr>
                                <th class="num">No</th>
                                <th class="book_name">책 제목</th>
                                <th class="author">저자</th>
                                <th class="publisher">상태</th>
                                <th class="date_b">대출일</th>
                                <th class="date_r">반납기한</th>
                                <th class="borrow">대출-반납</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($loans)): ?>
                                <tr>
                                    <td colspan="7">검색 결과가 없습니다.</td>
                                </tr>
                            <?php else: ?>    
                                <?php foreach($loans as $loan) :?>
                                    <tr>
                                        <td><?=$loan->seq?></td>
                                        <td><?=$loan->book_name?></td>
                                        <td><?=$loan->author?></td>
                                        <td>
                                            <?php if($loan->status == 'borrowed'): ?>
                                                대출중
                                            <?php elseif($loan->status == 'reserve'): ?>
                                                예약중
                                            <?php elseif($loan->status == 'return'): ?>
                                                반납 완료
                                            <?php endif; ?>
                                        </td>
                                        <td><?=$loan->loan_date?></td>
                                        <td><?= date('Y-m-d', strtotime($loan->return_date)) ?></td>
                                        <td>
                                        <?php if ($loan->status == 'borrowed'): ?>
                                            <button class="btn btn-fourth" 
                                                    onclick="confirmReturn(<?=$loan->seq?>, '<?=$loan->book_name?>')">
                                                반납 하기
                                            </button>
                                            <?php elseif ($loan->status == 'reserve'): ?>
                                                <!-- 예약한 도서 대출하기 -->
                                                <button class="btn btn-primary" >대출하기</button>
                                            <?php elseif ($loan->status == 'return'): ?>
                                                <!-- 반납 완료 -->
                                                <button class="btn btn-second" style="cursor: auto;"  >반납 완료</button>
                                                <div class="return-time">반납 시간: <?= date('Y-m-d H:i', strtotime($loan->return_time)) ?></div>
                                            <?php endif;?>
                                        </td>
                                        
                                    </tr>
                                    
                                <?php endforeach ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                 <!-- 페이징 링크 표시 -->
                 <div class="pagination">
                    <?php if($current_page > 1):?>
                        <a href="<?php echo site_url('status/' . ($current_page - 1)); ?>"><</a>
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
                    <a href="<?php echo site_url('status/' . $i . '?search=' . $search_query); ?>" 
                        <?php echo ($i == $current_page) ? 'class="active"' : ''; ?>>
                            <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>
                   

                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo site_url('status/' . ($current_page + 1)); ?>">></a>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <!-- content -->
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmReturn(bookSeq, bookName) {
            // 대출을 확인하는 confirm 창을 띄움
            var userConfirm = confirm("["+bookName + "] 을(를) 반납하시겠습니까?");
            
            if (userConfirm) {
                // AJAX 요청을 통해 대출을 진행
                $.ajax({
                    url: '<?= site_url("book/return") ?>', // AJAX 처리를 위한 URL 
                    type: 'POST',
                    data: { book_seq: bookSeq },
                    success: function(response) {
                        alert("["+bookName + "]  반납이 완료되었습니다.");
                        location.reload();
                    },
                    error: function() {
                        alert("반납 요청 중 오류가 발생했습니다. 다시 시도해주세요.");
                    }
                });
            } else {
                alert("반납이 취소되었습니다.");
            }
        }

    </script>


</body>
</html>