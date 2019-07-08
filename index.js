//ハンバーガーメニュー 共用
$(function(){
	if($('#slide_gallery').length){
		$('#slide_gallery li').each(function(){
			$('#pl').append($('<li>●</li>').attr('data-img',$('img',this).attr('src')));
		});

			let wid = 60;

		$('#plus_minus').width($('#pl li').length * wid);
		$('#pl li:first-child').addClass('act');

		$('#next').click(function(){
			$('#slide_gallery:not(:animated)').animate({'margin-left':-1 * $('#main_photo').width()}
			,'swing',function(){
				$('#slide_gallery').append($('#slide_gallery li:first-child'));
				$('#slide_gallery').css('margin-left','0');
				$('#pl li.act').removeClass('act');
				$('#pl li[data-img="' + $('#slide_gallery li:first-child img').attr('src') + '"]').addClass('act');
			});
		});

		$('#prev').click(function(){
			$('#slide_gallery').css('margin-left',-1 * $('#main_photo').width()).prepend($('#slide_gallery li:last-child')).animate({'margin-left':'0'}
			,function(){
				$('#pl li.act').removeClass('act');
				$('#pl li[data-img="' + $('#slide_gallery li:first-child img').attr('src') + '"]').addClass('act');
				});
		});

		/*let interval = setInterval(function(){
			$('#next').click();
		},5000);*/

		$('#main_photo').hover(function(){
			$('#prev,#next').css('display','block');
			interval = clearInterval(interval);
		},function(){
			$('#prev,#next').css('display','none');
			interval = setInterval(function(){
				$('#next').click();
			},5000);
		});

	}

	if($('#humburger').css('display') == 'none'){//PC用
		$('.slider').hover(function(){
			$("ul li",this).has('a').not(':animated').slideDown('fast');
		},function(){
			$("ul li",this).has('a').slideUp('fast');
		});
	}
	if($('#humburger').css('display') == 'block'){//スマホ用
		$('nav').css('margin-left','-70%');
		$('#humburger').click(function(){
			$(this).toggleClass('hum');
			if($('#black').css('display') == 'none'){
				$('nav').animate({'margin-left':'0'});
				$('#black').css('display','block');
				$('body').css('overflow','hidden');
			}else{
				$('nav').animate({'margin-left':'-70%'});
				$('#black').css('display','none');
				$('body').css('overflow','visible');
			}
		})
	}
	$(window).resize(function(){//nav！要改良！
		var x = $(window).width();
		if(x > 480 || $('nav').css('margin-left') == '-70%'){
			$('nav').css('margin-left','0');
		}else if(x < 481 || $('nav').css('margin-left') == '-70%'){
			$('nav').css('margin-left','-70%');
		}
	});
	$('#file').change(function(){//画像変更
		var file = $(this).prop('files')[0];
		var fr = new FileReader();
		fr.onload = function(){
			$('#new_image li:nth-child(3)').css('background-image','url(' + fr.result + ')');
			$('#new_image li:nth-child(3)').css('background-size','120px 120px');
		};
		fr.readAsDataURL(file);
	});

	$('#fix').click(function(){//TOPへ戻る
		$('html,body').animate({'scrollTop':'0'});
	});

	$(window).scroll(function(){//ページ最下部のメッセージ
		let doc = $(document).height();
		let win = $(window).height();
		let sct = Math.ceil($(window).scrollTop());
		let total = doc - win;

		if(total <= sct){
			$('#messa:not(:animated)').animate({'opacity':'1'},1000);
		}else{
			$('#messa:not(:animated)').css('opacity','0');
		}
	});
});
