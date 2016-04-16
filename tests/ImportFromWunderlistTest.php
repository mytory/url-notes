<?php

use App\Console\Commands\ImportFromWunderlist;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportFromWunderlistTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('URL Notes');
    }

    public function testParseWunderlistTitle(){
        $importFromWunderlist = new ImportFromWunderlist;

        $title = '#서비스 무료 서버 모니터링 툴 소개 http://www.sharedit.co.kr/%EB%AC%B4%EB%A3%8C-%EC%84%9C%EB%B2%84-%EB%AA%A8%EB%8B%88%ED%84%B0%EB%A7%81%EC%9D%98-%EC%8B%9C%EB%8C%80%EA%B0%80-%EC%98%A4%EB%8B%A4-%EC%99%80%ED%83%ADwhatap/';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['서비스'], $result['tags']);
        $this->assertEquals('무료 서버 모니터링 툴 소개', $result['title']);
        $this->assertEquals('http://www.sharedit.co.kr/%EB%AC%B4%EB%A3%8C-%EC%84%9C%EB%B2%84-%EB%AA%A8%EB%8B%88%ED%84%B0%EB%A7%81%EC%9D%98-%EC%8B%9C%EB%8C%80%EA%B0%80-%EC%98%A4%EB%8B%A4-%EC%99%80%ED%83%ADwhatap/', $result['url']);

        $title = '#흥미 계산기라기 보다는 복사기에 가까운 컴퓨터 www.strchr.com/x86_machine_code_statistics';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['흥미'], $result['tags']);
        $this->assertEquals('계산기라기 보다는 복사기에 가까운 컴퓨터', $result['title']);
        $this->assertEquals('http://www.strchr.com/x86_machine_code_statistics', $result['url']);

        $title = '#흥미 #재미 계산기라기 보다는 복사기에 가까운 컴퓨터 www.strchr.com/x86_machine_code_statistics';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['흥미', '재미'], $result['tags']);
        $this->assertEquals('계산기라기 보다는 복사기에 가까운 컴퓨터', $result['title']);
        $this->assertEquals('http://www.strchr.com/x86_machine_code_statistics', $result['url']);

        $title = '#개발정보 고급 bash 스크립팅 가이드 http://cfile1.uf.tistory.com/attach/1733A9204B5B368C03045C';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['개발정보'], $result['tags']);
        $this->assertEquals('고급 bash 스크립팅 가이드', $result['title']);
        $this->assertEquals('http://cfile1.uf.tistory.com/attach/1733A9204B5B368C03045C', $result['url']);

        $title = '#개발정보 Flexbox 관련 글 모음 https://www.smashingmagazine.com/2016/02/the-flexbox-reading-list/';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['개발정보'], $result['tags']);
        $this->assertEquals('Flexbox 관련 글 모음', $result['title']);
        $this->assertEquals('https://www.smashingmagazine.com/2016/02/the-flexbox-reading-list/', $result['url']);

        $title = '#API Glosbe API (Dictionary & Translation API) https://glosbe.com/a-api';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['API'], $result['tags']);
        $this->assertEquals('Glosbe API (Dictionary & Translation API)', $result['title']);
        $this->assertEquals('https://glosbe.com/a-api', $result['url']);

        $title = '#개발정보 kill -9의 남용에 관해 https://web.archive.org/web/20140123111351/http://partmaps.org/era/unix/award.html#uuk9letter';
        $result = $importFromWunderlist->parse_title($title);
        $this->assertEquals(['개발정보'], $result['tags']);
        $this->assertEquals('kill -9의 남용에 관해', $result['title']);
        $this->assertEquals('https://web.archive.org/web/20140123111351/http://partmaps.org/era/unix/award.html#uuk9letter', $result['url']);


        $memo = <<<memo
1. 지난 5월 22일, 유엔 표현의 자유 특별보고관 데이비드 케이(David Kaye)는 ‘암호화와 익명성(encryption and anonymity)’에 대한 보고서(http://www.ohchr.org/EN/Issues/FreedomOpinion/Pages/DavidKaye.aspx )를 발표했습니다. 데이비드 케이는 전임 특별보고관이었던 프랑크 라 루에 이어, 지난 해 8월 임기를 시작했으며, 이번에 발표한 보고서는 그의 첫번째 보고서입니다. 이 보고서는 조만간 개최된 유엔 인권이사회에 제출될 예정입니다.

2. 이 보고서에서 데이비드 케이는 디지털 시대에 안전한 소통을 위해 암호화와 익명성이 어떤 역할을 하는지, 그것이 표현의 자유 및 프라이버시와 어떠한 관계가 있는지, 암호와와 익명성을 제약하는 현실의 문제는 무엇인지를 검토하고 있습니다.

3. 이 보고서에서 그는 “암호화와 익명성은 디지털 시대 표현의 자유권 행사를 위해 필요한 프라이버시와 보안을 제공한다”고 주장합니다. 그리고, 각 국가는 암호화와 익명성을 증진해야 하며, 이를 제한하지 말 것을 권고하고 있습니다.
데이비드 케이

4. 데이비드 케이의 이 보고서는 한국 사회에도 시사하는 바가 큽니다.


(1) 흥미롭게도 이 보고서에서는 익명성을 옹호한 각 국가/지역의 법적 해석의 사례로 2012년 한법재판소의 인터넷 실명제(제한적 본인확인제) 위헌 결정을 인용하고 있습니다.

(2) 이 보고서는 “온라인에서 익명성의 금지는 표현의 자유권을 침해한다”고 하면서, 익명 표현의 자유를 금지한 국가들, 그리고 온라인 활동에 실명 등록을 요구하는 국가들의 사례를 들며, 비판하고 있습니다. 한국이 구체적으로 거명되지는 않았지만, 한국 역시 그러한 사례에 포함될 것입니다. 2012년 인터넷 실명제 위헌 결정에도 불구하고, 선거시기 인터넷 실명제, 게임 이용자에 대한 실명제 등이 여전히 존재하고 있기 때문입니다.

(3) 이 보고서에서 데이비드 케이는 “암호화와 익명성에 대한 제한은 특정한 사례에 국한되어야 하며, 적법성, 필요성, 비례성, 목적의 정당성 요건을 충족시켜야 하고, 특정한 제한에 법원의 명령을 요구해야” 한다고 권고하고 있습니다. 또한, “국가는 디지털 커뮤니케이션 및 온라인 서비스에 접근하는 조건으로 이용자의 식별을 요구해서는 안된다”고 권고하고 있습니다. 이에 비추어보면, 사전적으로 모든 잠재적 이용자에게 적용되는 한국의 본인 확인 제도는 분명 인권을 침해하는 제도입니다.

(4) 이 보고서는 또한 각 국가는 모바일 이용자에 대한 SIM 카드 등록을 요구하지 말아야 한다고 권고하고 있습니다. 그리고 이와 같은 강제적인 SIM 카드 등록은 합법적인 정부의 이해를 넘어, 개인과 언론인을 감시할 수 있는 역량을 정부에 제공할 수 있다고 우려하고 있습니다. 한국에서는 선불제, 후불제 관계없이 모바일 서비스 이용을 위해서 이용자의 신원 확인을 하고, 심지어 통신사의 주민등록번호 수집까지 허용하고 있습니다.

5. 진보네트워크센터는 데이비드 케이의 이번 보고서 발표를 환영합니다. 그리고, 프랑크 라 루에 이어, 데이비드 케이 역시 디지털 시대 표현의 자유와 프라이버시 옹호를 위해 많은 역할을 해줄 것을 기대합니다. 또한, 한국 정부와 국회는 인터넷 및 통신 실명제 등 한국의 법률들이 얼마나 국민의 기본권을 침해하고 있는지 이해할 필요가 있으며, 유엔 표현의 자유 특별보고관의 권고에 따라 문제의 법률들을 개정할 것을 촉구합니다.

* Report of the Special Rapporteur on the promotion and protection of the right to freedom of opinion and expression, David Kaye
http://www.ohchr.org/EN/HRBodies/HRC/RegularSessions/Session29/Documents/A.HRC.29.32_AEV.doc
* David Kaye 소개 : http://www.ohchr.org/EN/Issues/FreedomOpinion/Pages/DavidKaye.aspx

--

 [진보네트워크 메일링리스트] http://list.jinbo.net
메일링리스트 탈퇴는 http://list.jinbo.net/unsub.php?listname=jpress 에서 메일링리스트 주소와 이메일을 입력하시면 탈퇴하실 수 있습니다.
진보네트워크센터 메일링리스트 이용매뉴얼: http://list.jinbo.net/manual/
memo;

        $result = $importFromWunderlist->parse_memo($memo);
        $this->assertEquals('http://www.ohchr.org/EN/Issues/FreedomOpinion/Pages/DavidKaye.aspx', $result['url']);
        $this->assertEquals($memo, $result['content']);
    }
}
