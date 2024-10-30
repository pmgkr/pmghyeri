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
                <form action="/book" method="get" class="search_form">
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
                                <th class="publisher">출판사</th>
                                <th class="status">상태</th>
                                <th class="borrow">대출-예약</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($books)): ?>
                                <tr>
                                    <td colspan="6">검색 결과가 없습니다.</td>
                                </tr>
                            <?php else: ?>    
                                <?php foreach($books as $book) :?>
                                    <tr>
                                        <td><?=$book->seq?></td>
                                        <td><?=$book->book_name?></td>
                                        <td><?=$book->author?></td>
                                        <td><?=$book->publisher?></td>
                                        <td>
                                            <?php if($book->status == 'available'): ?>
                                                대출 가능
                                            <?php elseif($book->status == 'inspect'): ?>
                                                점검 중
                                            <?php else: ?>
                                                대출 중
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($book->status == 'available'): ?>
                                                <button class="btn btn-primary" 
                                                        onclick="confirmLoan('<?= htmlspecialchars($book->seq, ENT_QUOTES) ?>', '<?= htmlspecialchars($book->book_name, ENT_QUOTES) ?>')">
                                                    대출하기
                                                </button>
                                            <?php elseif ($book->status == 'borrowed'): ?>
                                                <!-- 대출 불가 시 비활성화된 버튼 -->
                                                <button class="btn btn-second" >예약하기</button>
                                            <?php elseif ($book->status == 'inspect'): ?>
                                                <!-- 대출 불가 시 비활성화된 버튼 -->
                                                <button class="btn btn-third" disabled>대출불가</button>
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
                    <a href="<?php echo site_url('book/' . $i . '?search=' . $search_query); ?>" 
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

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmLoan(bookSeq, bookName) {
            // 대출을 확인하는 confirm 창을 띄움
            var userConfirm = confirm("["+bookName + "] 을(를) 대출하시겠습니까?");
            
            if (userConfirm) {
                // AJAX 요청을 통해 대출을 진행
                $.ajax({
                    url: '<?= site_url("book/loan_ajax") ?>', // AJAX 처리를 위한 URL (컨트롤러에 추가할 메서드)
                    type: 'POST',
                    data: { book_seq: bookSeq },
                    success: function(response) {
                        // 서버에서 응답을 받아 성공 메시지 표시
                        alert("["+bookName + "]  대출이 완료되었습니다.");
                        // 대출 상태를 갱신하기 위해 페이지를 새로고침
                        location.reload();
                    },
                    error: function() {
                        alert("대출 요청 중 오류가 발생했습니다. 다시 시도해주세요.");
                    }
                });
            } else {
                // 취소를 눌렀을 경우 아무 작업도 하지 않음
                alert("대출이 취소되었습니다.");
            }
        }
    </script>


</body>
</html>