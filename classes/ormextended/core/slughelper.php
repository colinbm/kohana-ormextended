<?php defined('SYSPATH') or die('No direct script access.');

class OrmExtended_Core_SlugHelper {

	public static function sluggify($string) {
		// convert chinese text to pinyin - this will need further processing
		// as it will contain accents.
		$string = self::chinese_to_pinyin($string);

		// convert cyrillic (russian, etc) to roman
		$string = self::cyrillic_to_roman($string);

		// deal with letters with accents
		$accents = array(
			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a', 'Å' => 'a',
			'Æ' => 'a', 'Ç' => 'c', 'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e',
			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'Ð' => 'd', 'Ñ' => 'n',
			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o', 'Ø' => 'o',
			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'Ý' => 'y', 'Þ' => 'b',
			'ß' => 's', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
			'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
			'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd',
			'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
			'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y',
			'þ' => 'b', 'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', 'ā' => 'a', 'ī' => 'i',
			'ž' => 'z', 'ž' => 'z', 'č' => 'c', 'č' => 'c', 'š' => 's', 'š' => 's',
			'è' => 'e', 'è' => 'e', 'ě' => 'e', 'ě' => 'e', 'ô' => 'o', 'ǫ' => 'o',
			'ę' => 'e', 'ü' => 'u'
		);
		foreach($accents as $k => $v) {
			$string = str_replace($k, $v, $string);
		}

		// lowercase
		$string = strtolower($string);

		// replace & character with "and"
		$string = str_replace('&', "and", $string);

		// replace spaces with -
		$string = preg_replace("/ /", "-", $string);

		// strip out anything that isn't an acceptable character
		$string = preg_replace('/[^a-z0-9-_]/', '', $string);

		// get rid of any hyphen at the start/end
		$string = trim($string, '-');

		// multiple hyphens together should be single
		$string = preg_replace('/-+/', '-', $string);

		// and return.
		return $string;

	}
	
	/*
	 * string cyrillic_to_roman($string)
	 *
	 * Converts cyrillic text to the roman alphabet
	 * 
	 */

	public static function cyrillic_to_roman($string) {
		$translations = array(
			'А' => 'a',  'Б' => 'b',  'В' => 'v',  'Г' => 'g',
			'Д' => 'd',  'Е' => 'e',  'Ё' => 'ë',  'Ж' => 'ž',
			'З' => 'z',  'И' => 'i',  'Й' => 'j',  'К' => 'k',
			'Л' => 'l',  'М' => 'm',  'Н' => 'n',  'О' => 'o',
			'П' => 'p',  'Р' => 'r',  'С' => 's',  'Т' => 't',
			'У' => 'u',  'Ф' => 'f',  'Х' => 'x',  'Ц' => 'c',
			'Ч' => 'č',  'Ш' => 'š',  'Щ' => 'šč', 'Ъ' => 'ʺ',
			'Ы' => 'y',  'Ь' => 'ʹ',  'Э' => 'è',  'Ю' => 'ju',
			'Я' => 'ja', 'І' => 'i',  'Ѳ' => 'f',  'Ѣ' => 'ě',
			'Ѵ' => 'i',  'Ѕ' => 'dz', 'Ѯ' => 'ks', 'Ѱ' => 'ps',
			'а' => 'a',  'б' => 'b',  'в' => 'v',  'г' => 'g',
			'д' => 'd',  'е' => 'e',  'ё' => 'ë',  'ж' => 'ž',
			'з' => 'z',  'и' => 'i',  'й' => 'j',  'к' => 'k',
			'л' => 'l',  'м' => 'm',  'н' => 'n',  'о' => 'o',
			'п' => 'p',  'р' => 'r',  'с' => 's',  'т' => 't',
			'у' => 'u',  'ф' => 'f',  'х' => 'x',  'ц' => 'c',
			'ч' => 'č',  'ш' => 'š',  'щ' => 'šč', 'ъ' => 'ʺ',
			'ы' => 'y',  'ь' => 'ʹ',  'э' => 'è',  'ю' => 'ju',
			'я' => 'ja', 'і' => 'i',  'ѳ' => 'f',  'ѣ' => 'ě',
			'ѵ' => 'i',  'ѕ' => 'dz', 'ѯ' => 'ks', 'ѱ' => 'ps',
			'Ѡ' => 'ô',  'ѡ' => 'o',  'Ѫ' => 'ǫ',  'ѫ' => 'u',
			'Ѧ' => 'ę',  'ѧ' => 'ja', 'Ѭ' => 'jǫ', 'ѭ' => 'ju',
			'Ѩ' => 'ję', 'ѩ' => 'ja'
		);
		foreach($translations as $k => $v) {
			$string = str_replace($k, $v, $string);
		}
		return $string;
	}
	
	/*
	 * string chinese_to_pinyin($string)
	 *
	 * Converts chinese text to the pinyin equivelent
	 * 
	 */

	public static function chinese_to_pinyin($string) {
		$pinyin = array(
			array(
				'from' => array('阿', '啊', '呵', '腌', '吖', '锕'),
				'to' => 'ā'
			),
			array(
				'from' => array('啊', '呵', '嗄'),
				'to' => 'á'
			),
			array(
				'from' => array('啊', '呵'),
				'to' => 'ǎ'
			),
			array(
				'from' => array('啊', '呵'),
				'to' => 'à'
			),
			array(
				'from' => array('阿', '啊', '呵'),
				'to' => 'a'
			),
			array(
				'from' => array('哀', '挨', '埃', '唉', '哎', '捱', '锿'),
				'to' => 'āi'
			),
			array(
				'from' => array('挨', '癌', '皑', '捱'),
				'to' => 'ái'
			),
			array(
				'from' => array('矮', '哎', '蔼', '霭', '嗳'),
				'to' => 'ǎi'
			),
			array(
				'from' => array('爱', '碍', '艾', '唉', '哎', '隘', '暧', '嗳', '瑷', '嗌', '嫒', '砹'),
				'to' => 'ài'
			),
			array(
				'from' => array('安', '谙', '鞍', '氨', '庵', '桉', '鹌', '广', '厂'),
				'to' => 'ān'
			),
			array(
				'from' => array('俺', '铵', '揞', '埯'),
				'to' => 'ǎn'
			),
			array(
				'from' => array('案', '按', '暗', '岸', '黯', '胺', '犴'),
				'to' => 'àn'
			),
			array(
				'from' => array('肮'),
				'to' => 'āng'
			),
			array(
				'from' => array('昂'),
				'to' => 'áng'
			),
			array(
				'from' => array('盎'),
				'to' => 'àng'
			),
			array(
				'from' => array('熬', '凹'),
				'to' => 'āo'
			),
			array(
				'from' => array('熬', '敖', '嚣', '嗷', '鏖', '鳌', '翱', '獒', '聱', '螯', '廒', '遨'),
				'to' => 'áo'
			),
			array(
				'from' => array('袄', '拗', '媪'),
				'to' => 'ǎo'
			),
			array(
				'from' => array('奥', '澳', '傲', '懊', '坳', '拗', '骜', '岙', '鏊'),
				'to' => 'ào'
			),
			array(
				'from' => array('八', '吧', '巴', '叭', '芭', '扒', '疤', '笆', '粑', '岜', '捌'),
				'to' => 'bā'
			),
			array(
				'from' => array('八', '拔', '跋', '茇', '菝', '魃'),
				'to' => 'bá'
			),
			array(
				'from' => array('把', '靶', '钯'),
				'to' => 'bǎ'
			),
			array(
				'from' => array('把', '爸', '罢', '霸', '坝', '耙', '灞', '鲅'),
				'to' => 'bà'
			),
			array(
				'from' => array('吧', '罢'),
				'to' => 'ba'
			),
			array(
				'from' => array('掰'),
				'to' => 'bāi'
			),
			array(
				'from' => array('白'),
				'to' => 'bái'
			),
			array(
				'from' => array('百', '摆', '伯', '柏', '佰', '捭'),
				'to' => 'bǎi'
			),
			array(
				'from' => array('败', '拜', '呗', '稗'),
				'to' => 'bài'
			),
			array(
				'from' => array('般', '班', '搬', '斑', '颁', '扳', '瘢', '癍'),
				'to' => 'bān'
			),
			array(
				'from' => array('版', '板', '阪', '坂', '钣', '舨'),
				'to' => 'bǎn'
			),
			array(
				'from' => array('办', '半', '伴', '扮', '瓣', '拌', '绊'),
				'to' => 'bàn'
			),
			array(
				'from' => array('帮', '邦', '浜', '梆'),
				'to' => 'bāng'
			),
			array(
				'from' => array('膀', '榜', '绑'),
				'to' => 'bǎng'
			),
			array(
				'from' => array('棒', '膀', '傍', '磅', '谤', '镑', '蚌', '蒡'),
				'to' => 'bàng'
			),
			array(
				'from' => array('包', '胞', '炮', '剥', '褒', '苞', '孢', '煲', '龅'),
				'to' => 'bāo'
			),
			array(
				'from' => array('薄', '雹'),
				'to' => 'báo'
			),
			array(
				'from' => array('保', '宝', '饱', '堡', '葆', '褓', '鸨'),
				'to' => 'bǎo'
			),
			array(
				'from' => array('报', '暴', '抱', '爆', '鲍', '曝', '刨', '瀑', '豹', '趵'),
				'to' => 'bào'
			),
			array(
				'from' => array('背', '悲', '杯', '碑', '卑', '陂', '埤', '萆', '鹎'),
				'to' => 'bēi'
			),
			array(
				'from' => array('北'),
				'to' => 'běi'
			),
			array(
				'from' => array('被', '备', '背', '辈', '倍', '贝', '蓓', '惫', '悖', '狈', '焙', '邶', '钡', '孛', '碚', '褙', '鐾', '鞴'),
				'to' => 'bèi'
			),
			array(
				'from' => array('臂', '呗'),
				'to' => 'bei'
			),
			array(
				'from' => array('奔', '贲', '锛'),
				'to' => 'bēn'
			),
			array(
				'from' => array('本', '苯', '畚'),
				'to' => 'běn'
			),
			array(
				'from' => array('奔', '笨', '夯', '坌'),
				'to' => 'bèn'
			),
			array(
				'from' => array('崩', '绷', '嘣'),
				'to' => 'bēng'
			),
			array(
				'from' => array('甭'),
				'to' => 'béng'
			),
			array(
				'from' => array('绷'),
				'to' => 'běng'
			),
			array(
				'from' => array('绷', '蹦', '迸', '甏', '泵', '蚌'),
				'to' => 'bèng'
			),
			array(
				'from' => array('逼'),
				'to' => 'bī'
			),
			array(
				'from' => array('鼻', '荸'),
				'to' => 'bí'
			),
			array(
				'from' => array('比', '笔', '彼', '鄙', '匕', '俾', '妣', '吡', '秕', '舭'),
				'to' => 'bǐ'
			),
			array(
				'from' => array('必', '毕', '币', '秘', '避', '闭', '壁', '臂', '弊', '辟', '碧', '拂', '毙', '蔽', '庇', '璧', '敝', '泌', '陛', '弼', '篦', '婢', '愎', '痹', '铋', '裨', '濞', '髀', '庳', '毖', '滗', '蓖', '埤', '芘', '嬖', '荜', '贲', '畀', '萆', '薜', '筚', '箅', '哔', '襞', '跸', '狴'),
				'to' => 'bì'
			),
			array(
				'from' => array('编', '边', '鞭', '砭', '煸', '蝙', '笾', '鳊'),
				'to' => 'biān'
			),
			array(
				'from' => array('贬', '扁', '匾', '碥', '窆', '褊'),
				'to' => 'biǎn'
			),
			array(
				'from' => array('便', '变', '遍', '辩', '辨', '辫', '卞', '苄', '汴', '忭', '弁', '缏'),
				'to' => 'biàn'
			),
			array(
				'from' => array('边'),
				'to' => 'bian'
			),
			array(
				'from' => array('标', '彪', '勺', '镖', '膘', '骠', '镳', '杓', '飚', '飑', '飙', '瘭', '髟'),
				'to' => 'biāo'
			),
			array(
				'from' => array('表', '裱', '婊'),
				'to' => 'biǎo'
			),
			array(
				'from' => array('鳔'),
				'to' => 'biào'
			),
			array(
				'from' => array('憋', '瘪', '鳖'),
				'to' => 'biē'
			),
			array(
				'from' => array('别', '蹩'),
				'to' => 'bié'
			),
			array(
				'from' => array('瘪'),
				'to' => 'biě'
			),
			array(
				'from' => array('别'),
				'to' => 'biè'
			),
			array(
				'from' => array('宾', '滨', '彬', '斌', '缤', '濒', '槟', '傧', '玢', '豳', '镔'),
				'to' => 'bīn'
			),
			array(
				'from' => array('鬓', '殡', '摈', '膑', '髌'),
				'to' => 'bìn'
			),
			array(
				'from' => array('并', '兵', '冰', '槟'),
				'to' => 'bīng'
			),
			array(
				'from' => array('饼', '屏', '丙', '柄', '秉', '炳', '禀', '邴'),
				'to' => 'bǐng'
			),
			array(
				'from' => array('并', '病', '摒'),
				'to' => 'bìng'
			),
			array(
				'from' => array('般', '波', '播', '拨', '剥', '玻', '饽', '菠', '钵', '趵'),
				'to' => 'bō'
			),
			array(
				'from' => array('百', '博', '伯', '勃', '薄', '泊', '柏', '驳', '魄', '脖', '搏', '膊', '舶', '礴', '帛', '铂', '箔', '渤', '钹', '孛', '亳', '鹁', '踣'),
				'to' => 'bó'
			),
			array(
				'from' => array('簸', '跛'),
				'to' => 'bǒ'
			),
			array(
				'from' => array('薄', '柏', '簸', '掰', '擘', '檗'),
				'to' => 'bò'
			),
			array(
				'from' => array('卜', '啵'),
				'to' => 'bo'
			),
			array(
				'from' => array('逋', '晡', '钸'),
				'to' => 'bū'
			),
			array(
				'from' => array('不', '醭'),
				'to' => 'bú'
			),
			array(
				'from' => array('补', '捕', '堡', '卜', '哺', '卟'),
				'to' => 'bǔ'
			),
			array(
				'from' => array('不', '部', '布', '步', '怖', '簿', '埔', '埠', '瓿', '钚'),
				'to' => 'bù'
			),
			array(
				'from' => array('擦', '拆', '嚓'),
				'to' => 'cā'
			),
			array(
				'from' => array('礤'),
				'to' => 'cǎ'
			),
			array(
				'from' => array('猜'),
				'to' => 'cāi'
			),
			array(
				'from' => array('才', '财', '材', '裁'),
				'to' => 'cái'
			),
			array(
				'from' => array('采', '彩', '踩', '睬'),
				'to' => 'cǎi'
			),
			array(
				'from' => array('采', '菜', '蔡'),
				'to' => 'cài'
			),
			array(
				'from' => array('参', '餐', '骖'),
				'to' => 'cān'
			),
			array(
				'from' => array('残', '惭', '蚕'),
				'to' => 'cán'
			),
			array(
				'from' => array('惨', '黪'),
				'to' => 'cǎn'
			),
			array(
				'from' => array('惨', '灿', '掺', '璨', '孱', '粲'),
				'to' => 'càn'
			),
			array(
				'from' => array('苍', '仓', '沧', '舱', '伧'),
				'to' => 'cāng'
			),
			array(
				'from' => array('藏'),
				'to' => 'cáng'
			),
			array(
				'from' => array('操', '糙'),
				'to' => 'cāo'
			),
			array(
				'from' => array('曹', '槽', '嘈', '漕', '螬', '艚'),
				'to' => 'cáo'
			),
			array(
				'from' => array('草'),
				'to' => 'cǎo'
			),
			array(
				'from' => array('策', '测', '侧', '厕', '册', '恻'),
				'to' => 'cè'
			),
			array(
				'from' => array('参'),
				'to' => 'cēn'
			),
			array(
				'from' => array('岑', '涔'),
				'to' => 'cén'
			),
			array(
				'from' => array('噌'),
				'to' => 'cēng'
			),
			array(
				'from' => array('曾', '层'),
				'to' => 'céng'
			),
			array(
				'from' => array('蹭'),
				'to' => 'cèng'
			),
			array(
				'from' => array('差', '插', '叉', '碴', '喳', '嚓', '杈', '馇', '锸'),
				'to' => 'chā'
			),
			array(
				'from' => array('查', '察', '茶', '叉', '茬', '碴', '楂', '猹', '搽', '槎', '檫'),
				'to' => 'chá'
			),
			array(
				'from' => array('叉', '衩', '镲'),
				'to' => 'chǎ'
			),
			array(
				'from' => array('差', '刹', '叉', '诧', '岔', '衩', '杈', '汊', '姹'),
				'to' => 'chà'
			),
			array(
				'from' => array('差', '拆', '钗'),
				'to' => 'chāi'
			),
			array(
				'from' => array('柴', '豺', '侪'),
				'to' => 'chái'
			),
			array(
				'from' => array('虿', '瘥'),
				'to' => 'chài'
			),
			array(
				'from' => array('搀', '掺', '觇'),
				'to' => 'chān'
			),
			array(
				'from' => array('单', '缠', '禅', '蝉', '馋', '潺', '蟾', '婵', '谗', '廛', '孱', '镡', '澶', '躔'),
				'to' => 'chán'
			),
			array(
				'from' => array('产', '铲', '阐', '谄', '冁', '蒇', '骣'),
				'to' => 'chǎn'
			),
			array(
				'from' => array('颤', '忏', '羼'),
				'to' => 'chàn'
			),
			array(
				'from' => array('昌', '娼', '猖', '伥', '阊', '菖', '鲳'),
				'to' => 'chāng'
			),
			array(
				'from' => array('长', '场', '常', '尝', '肠', '偿', '倘', '裳', '嫦', '徜', '苌'),
				'to' => 'cháng'
			),
			array(
				'from' => array('场', '厂', '敞', '氅', '昶', '惝'),
				'to' => 'chǎng'
			),
			array(
				'from' => array('唱', '畅', '倡', '怅', '鬯'),
				'to' => 'chàng'
			),
			array(
				'from' => array('超', '抄', '吵', '钞', '绰', '剿', '焯', '怊'),
				'to' => 'chāo'
			),
			array(
				'from' => array('朝', '潮', '嘲', '巢', '晁'),
				'to' => 'cháo'
			),
			array(
				'from' => array('炒', '吵'),
				'to' => 'chǎo'
			),
			array(
				'from' => array('耖'),
				'to' => 'chào'
			),
			array(
				'from' => array('车', '砗'),
				'to' => 'chē'
			),
			array(
				'from' => array('尺', '扯'),
				'to' => 'chě'
			),
			array(
				'from' => array('彻', '撤', '澈', '掣', '坼'),
				'to' => 'chè'
			),
			array(
				'from' => array('郴', '琛', '嗔', '抻'),
				'to' => 'chēn'
			),
			array(
				'from' => array('陈', '沉', '晨', '沈', '尘', '臣', '辰', '橙', '忱', '谌', '宸'),
				'to' => 'chén'
			),
			array(
				'from' => array('碜'),
				'to' => 'chěn'
			),
			array(
				'from' => array('称', '趁', '衬', '秤', '谶', '榇', '龀'),
				'to' => 'chèn'
			),
			array(
				'from' => array('伧'),
				'to' => 'chen'
			),
			array(
				'from' => array('称', '撑', '秤', '瞠', '噌', '铛', '柽', '蛏'),
				'to' => 'chēng'
			),
			array(
				'from' => array('成', '城', '程', '承', '诚', '盛', '乘', '呈', '惩', '澄', '橙', '丞', '埕', '枨', '塍', '铖', '裎', '酲'),
				'to' => 'chéng'
			),
			array(
				'from' => array('逞', '骋', '裎'),
				'to' => 'chěng'
			),
			array(
				'from' => array('称', '秤'),
				'to' => 'chèng'
			),
			array(
				'from' => array('吃', '痴', '哧', '嗤', '蚩', '笞', '鸱', '媸', '螭', '眵', '魑'),
				'to' => 'chī'
			),
			array(
				'from' => array('持', '迟', '池', '驰', '匙', '弛', '踟', '墀', '茌', '篪', '坻'),
				'to' => 'chí'
			),
			array(
				'from' => array('尺', '齿', '耻', '侈', '褫', '豉'),
				'to' => 'chǐ'
			),
			array(
				'from' => array('赤', '斥', '翅', '啻', '炽', '敕', '叱', '饬', '傺', '彳', '瘛'),
				'to' => 'chì'
			),
			array(
				'from' => array('冲', '充', '涌', '憧', '忡', '艟', '舂', '茺'),
				'to' => 'chōng'
			),
			array(
				'from' => array('种', '重', '崇', '虫'),
				'to' => 'chóng'
			),
			array(
				'from' => array('宠'),
				'to' => 'chǒng'
			),
			array(
				'from' => array('冲', '铳'),
				'to' => 'chòng'
			),
			array(
				'from' => array('抽', '瘳'),
				'to' => 'chōu'
			),
			array(
				'from' => array('愁', '仇', '筹', '酬', '绸', '踌', '惆', '畴', '稠', '帱', '俦', '雠'),
				'to' => 'chóu'
			),
			array(
				'from' => array('丑', '瞅'),
				'to' => 'chǒu'
			),
			array(
				'from' => array('臭'),
				'to' => 'chòu'
			),
			array(
				'from' => array('出', '初', '樗'),
				'to' => 'chū'
			),
			array(
				'from' => array('除', '厨', '躇', '橱', '雏', '锄', '蜍', '刍', '滁', '蹰'),
				'to' => 'chú'
			),
			array(
				'from' => array('处', '楚', '储', '础', '杵', '褚', '楮'),
				'to' => 'chǔ'
			),
			array(
				'from' => array('处', '触', '畜', '矗', '怵', '搐', '绌', '黜', '亍', '憷'),
				'to' => 'chù'
			),
			array(
				'from' => array('揣', '搋'),
				'to' => 'chuāi'
			),
			array(
				'from' => array('揣'),
				'to' => 'chuǎi'
			),
			array(
				'from' => array('揣', '啜', '踹', '嘬', '膪'),
				'to' => 'chuài'
			),
			array(
				'from' => array('穿', '川', '巛', '氚'),
				'to' => 'chuān'
			),
			array(
				'from' => array('传', '船', '遄', '椽', '舡'),
				'to' => 'chuán'
			),
			array(
				'from' => array('喘', '舛'),
				'to' => 'chuǎn'
			),
			array(
				'from' => array('串', '钏'),
				'to' => 'chuàn'
			),
			array(
				'from' => array('创', '窗', '疮'),
				'to' => 'chuāng'
			),
			array(
				'from' => array('床', '幢'),
				'to' => 'chuáng'
			),
			array(
				'from' => array('闯'),
				'to' => 'chuǎng'
			),
			array(
				'from' => array('创', '怆'),
				'to' => 'chuàng'
			),
			array(
				'from' => array('吹', '炊'),
				'to' => 'chuī'
			),
			array(
				'from' => array(),
				'to' => ''
			),
			array(
				'from' => array('垂', '锤', '捶', '陲', '椎', '槌', '棰'),
				'to' => 'chuí'
			),
			array(
				'from' => array('春', '椿', '蝽'),
				'to' => 'chūn'
			),
			array(
				'from' => array('纯', '唇', '醇', '淳', '鹑', '莼'),
				'to' => 'chún'
			),
			array(
				'from' => array('蠢'),
				'to' => 'chǔn'
			),
			array(
				'from' => array('戳', '踔'),
				'to' => 'chuō'
			),
			array(
				'from' => array('绰', '啜', '辍', '龊'),
				'to' => 'chuò'
			),
			array(
				'from' => array('差', '刺', '疵', '呲'),
				'to' => 'cī'
			),
			array(
				'from' => array('词', '辞', '慈', '磁', '瓷', '兹', '茨', '雌', '祠', '茈', '鹚', '糍'),
				'to' => 'cí'
			),
			array(
				'from' => array('此'),
				'to' => 'cǐ'
			),
			array(
				'from' => array('次', '刺', '赐', '伺'),
				'to' => 'cì'
			),
			array(
				'from' => array('从', '匆', '聪', '葱', '囱', '苁', '骢', '璁', '枞'),
				'to' => 'cōng'
			),
			array(
				'from' => array('从', '丛', '琮', '淙'),
				'to' => 'cóng'
			),
			array(
				'from' => array('凑', '楱', '辏', '腠'),
				'to' => 'còu'
			),
			array(
				'from' => array('粗'),
				'to' => 'cū'
			),
			array(
				'from' => array('徂', '殂'),
				'to' => 'cú'
			),
			array(
				'from' => array('促', '簇', '醋', '卒', '猝', '蹴', '蹙', '蔟', '酢'),
				'to' => 'cù'
			),
			array(
				'from' => array('蹿', '撺', '汆', '镩'),
				'to' => 'cuān'
			),
			array(
				'from' => array('攒'),
				'to' => 'cuán'
			),
			array(
				'from' => array('窜', '篡', '爨'),
				'to' => 'cuàn'
			),
			array(
				'from' => array('衰', '催', '摧', '崔', '隹', '榱'),
				'to' => 'cuī'
			),
			array(
				'from' => array('璀'),
				'to' => 'cuǐ'
			),
			array(
				'from' => array('脆', '粹', '萃', '翠', '瘁', '悴', '淬', '毳', '啐'),
				'to' => 'cuì'
			),
			array(
				'from' => array('村', '皴'),
				'to' => 'cūn'
			),
			array(
				'from' => array('存', '蹲'),
				'to' => 'cún'
			),
			array(
				'from' => array('忖'),
				'to' => 'cǔn'
			),
			array(
				'from' => array('寸'),
				'to' => 'cùn'
			),
			array(
				'from' => array('搓', '撮', '磋', '蹉'),
				'to' => 'cuō'
			),
			array(
				'from' => array('嵯', '矬', '痤', '瘥', '鹾'),
				'to' => 'cuó'
			),
			array(
				'from' => array('撮', '脞'),
				'to' => 'cuǒ'
			),
			array(
				'from' => array('错', '措', '挫', '厝', '锉'),
				'to' => 'cuò'
			),
			array(
				'from' => array('答', '搭', '嗒', '耷', '褡', '哒'),
				'to' => 'dā'
			),
			array(
				'from' => array('打', '达', '答', '瘩', '沓', '鞑', '怛', '笪', '靼', '妲'),
				'to' => 'dá'
			),
			array(
				'from' => array('打'),
				'to' => 'dǎ'
			),
			array(
				'from' => array('大'),
				'to' => 'dà'
			),
			array(
				'from' => array('塔', '疸'),
				'to' => 'da'
			),
			array(
				'from' => array('待', '呆', '呔'),
				'to' => 'dāi'
			),
			array(
				'from' => array('逮', '歹', '傣'),
				'to' => 'dǎi'
			),
			array(
				'from' => array('大', '代', '带', '待', '戴', '袋', '贷', '逮', '殆', '黛', '怠', '玳', '岱', '迨', '骀', '绐', '埭', '甙'),
				'to' => 'dài'
			),
			array(
				'from' => array('单', '担', '丹', '耽', '眈', '殚', '箪', '儋', '瘅', '聃', '郸'),
				'to' => 'dān'
			),
			array(
				'from' => array('担', '胆', '掸', '赕', '疸', '瘅'),
				'to' => 'dǎn'
			),
			array(
				'from' => array('但', '担', '石', '弹', '淡', '旦', '蛋', '诞', '惮', '啖', '澹', '氮', '萏', '瘅'),
				'to' => 'dàn'
			),
			array(
				'from' => array('当', '裆', '铛'),
				'to' => 'dāng'
			),
			array(
				'from' => array('党', '挡', '谠'),
				'to' => 'dǎng'
			),
			array(
				'from' => array('当', '荡', '档', '挡', '宕', '菪', '凼', '砀'),
				'to' => 'dàng'
			),
			array(
				'from' => array('刀', '叨', '忉', '氘'),
				'to' => 'dāo'
			),
			array(
				'from' => array('叨'),
				'to' => 'dáo'
			),
			array(
				'from' => array('导', '倒', '岛', '蹈', '捣', '祷'),
				'to' => 'dǎo'
			),
			array(
				'from' => array('到', '道', '倒', '悼', '盗', '稻', '焘', '帱', '纛'),
				'to' => 'dào'
			),
			array(
				'from' => array('得', '德', '锝'),
				'to' => 'dé'
			),
			array(
				'from' => array('的', '地', '得', '底'),
				'to' => 'de'
			),
			array(
				'from' => array('得'),
				'to' => 'děi'
			),
			array(
				'from' => array('登', '灯', '蹬', '噔', '簦'),
				'to' => 'dēng'
			),
			array(
				'from' => array('等', '戥'),
				'to' => 'děng'
			),
			array(
				'from' => array('邓', '凳', '瞪', '澄', '蹬', '磴', '镫', '嶝'),
				'to' => 'dèng'
			),
			array(
				'from' => array('提', '低', '滴', '堤', '嘀', '氐', '镝', '羝'),
				'to' => 'dī'
			),
			array(
				'from' => array('的', '敌', '迪', '笛', '涤', '嘀', '狄', '嫡', '翟', '荻', '籴', '觌', '镝'),
				'to' => 'dí'
			),
			array(
				'from' => array('底', '抵', '诋', '邸', '砥', '坻', '柢', '氐', '骶'),
				'to' => 'dǐ'
			),
			array(
				'from' => array('的', '地', '第', '帝', '弟', '递', '蒂', '缔', '谛', '睇', '棣', '娣', '碲', '绨'),
				'to' => 'dì'
			),
			array(
				'from' => array('嗲'),
				'to' => 'diǎ'
			),
			array(
				'from' => array('颠', '滇', '掂', '癫', '巅'),
				'to' => 'diān'
			),
			array(
				'from' => array('点', '典', '碘', '踮', '丶'),
				'to' => 'diǎn'
			),
			array(
				'from' => array('电', '店', '甸', '淀', '垫', '殿', '奠', '惦', '佃', '玷', '簟', '坫', '靛', '钿', '癜', '阽'),
				'to' => 'diàn'
			),
			array(
				'from' => array('雕', '刁', '凋', '叼', '貂', '碉', '鲷'),
				'to' => 'diāo'
			),
			array(
				'from' => array('鸟'),
				'to' => 'diǎo'
			),
			array(
				'from' => array('调', '掉', '吊', '钓', '铫', '铞'),
				'to' => 'diào'
			),
			array(
				'from' => array('爹', '跌', '踮'),
				'to' => 'diē'
			),
			array(
				'from' => array('叠', '迭', '碟', '谍', '蝶', '喋', '佚', '牒', '耋', '蹀', '堞', '瓞', '揲', '垤', '鲽'),
				'to' => 'dié'
			),
			array(
				'from' => array('丁', '盯', '钉', '叮', '町', '酊', '疔', '仃', '耵', '玎'),
				'to' => 'dīng'
			),
			array(
				'from' => array('顶', '鼎', '酊'),
				'to' => 'dǐng'
			),
			array(
				'from' => array('定', '订', '钉', '铤', '腚', '锭', '碇', '啶'),
				'to' => 'dìng'
			),
			array(
				'from' => array('丢', '铥'),
				'to' => 'diū'
			),
			array(
				'from' => array('东', '冬', '咚', '岽', '氡', '鸫'),
				'to' => 'dōng'
			),
			array(
				'from' => array('懂', '董', '硐'),
				'to' => 'dǒng'
			),
			array(
				'from' => array('动', '洞', '冻', '栋', '恫', '侗', '垌', '峒', '胨', '胴', '硐'),
				'to' => 'dòng'
			),
			array(
				'from' => array('都', '兜', '蔸', '篼'),
				'to' => 'dōu'
			),
			array(
				'from' => array('斗', '抖', '陡', '蚪'),
				'to' => 'dǒu'
			),
			array(
				'from' => array('读', '斗', '豆', '逗', '窦', '痘'),
				'to' => 'dòu'
			),
			array(
				'from' => array('都', '督', '嘟'),
				'to' => 'dū'
			),
			array(
				'from' => array('读', '独', '毒', '渎', '牍', '犊', '黩', '髑', '椟'),
				'to' => 'dú'
			),
			array(
				'from' => array('肚', '睹', '堵', '赌', '笃'),
				'to' => 'dǔ'
			),
			array(
				'from' => array('度', '渡', '肚', '杜', '妒', '镀', '芏', '蠹'),
				'to' => 'dù'
			),
			array(
				'from' => array('端'),
				'to' => 'duān'
			),
			array(
				'from' => array('短'),
				'to' => 'duǎn'
			),
			array(
				'from' => array('断', '段', '锻', '缎', '煅', '椴', '簖'),
				'to' => 'duàn'
			),
			array(
				'from' => array('堆'),
				'to' => 'duī'
			),
			array(
				'from' => array('对', '队', '兑', '敦', '碓', '憝', '怼', '镦'),
				'to' => 'duì'
			),
			array(
				'from' => array('吨', '敦', '蹲', '墩', '礅', '镦'),
				'to' => 'dūn'
			),
			array(
				'from' => array('盹', '趸'),
				'to' => 'dǔn'
			),
			array(
				'from' => array('顿', '盾', '钝', '炖', '遁', '沌', '囤', '砘'),
				'to' => 'dùn'
			),
			array(
				'from' => array('多', '咄', '哆', '掇', '裰'),
				'to' => 'duō'
			),
			array(
				'from' => array('度', '夺', '踱', '铎'),
				'to' => 'duó'
			),
			array(
				'from' => array('朵', '躲', '垛', '哚', '缍'),
				'to' => 'duǒ'
			),
			array(
				'from' => array('舵', '堕', '跺', '剁', '惰', '垛', '驮', '沲', '柁'),
				'to' => 'duò'
			),
			array(
				'from' => array('阿', '婀', '屙'),
				'to' => 'ē'
			),
			array(
				'from' => array('额', '俄', '哦', '鹅', '娥', '峨', '蛾', '讹', '莪', '锇'),
				'to' => 'é'
			),
			array(
				'from' => array('恶'),
				'to' => 'ě'
			),
			array(
				'from' => array('恶', '饿', '扼', '愕', '遏', '噩', '呃', '厄', '鄂', '轭', '颚', '鳄', '谔', '锷', '萼', '腭', '垩', '鹗', '苊', '阏'),
				'to' => 'è'
			),
			array(
				'from' => array('呃'),
				'to' => 'e'
			),
			array(
				'from' => array('诶'),
				'to' => 'éi'
			),
			array(
				'from' => array('诶'),
				'to' => 'ěi'
			),
			array(
				'from' => array('诶'),
				'to' => 'èi'
			),
			array(
				'from' => array('恩', '蒽'),
				'to' => 'ēn'
			),
			array(
				'from' => array('摁'),
				'to' => 'èn'
			),
			array(
				'from' => array('而', '儿', '鸸', '鲕'),
				'to' => 'ér'
			),
			array(
				'from' => array('尔', '耳', '迩', '饵', '洱', '珥', '铒'),
				'to' => 'ěr'
			),
			array(
				'from' => array('二', '贰', '佴'),
				'to' => 'èr'
			),
			array(
				'from' => array('发'),
				'to' => 'fā'
			),
			array(
				'from' => array('罚', '乏', '伐', '阀', '筏', '垡'),
				'to' => 'fá'
			),
			array(
				'from' => array('法', '砝'),
				'to' => 'fǎ'
			),
			array(
				'from' => array('发', '珐'),
				'to' => 'fà'
			),
			array(
				'from' => array('翻', '番', '帆', '藩', '幡', '蕃'),
				'to' => 'fān'
			),
			array(
				'from' => array('凡', '烦', '繁', '泛', '樊', '蕃', '燔', '矾', '蘩', '钒', '蹯'),
				'to' => 'fán'
			),
			array(
				'from' => array('反', '返'),
				'to' => 'fǎn'
			),
			array(
				'from' => array('饭', '犯', '范', '贩', '泛', '梵', '畈'),
				'to' => 'fàn'
			),
			array(
				'from' => array('方', '芳', '妨', '坊', '邡', '枋', '钫'),
				'to' => 'fāng'
			),
			array(
				'from' => array('房', '防', '妨', '坊', '肪', '鲂'),
				'to' => 'fáng'
			),
			array(
				'from' => array('访', '仿', '纺', '彷', '舫'),
				'to' => 'fǎng'
			),
			array(
				'from' => array('放'),
				'to' => 'fàng'
			),
			array(
				'from' => array('非', '飞', '啡', '菲', '扉', '霏', '妃', '绯', '蜚', '鲱'),
				'to' => 'fēi'
			),
			array(
				'from' => array('肥', '腓', '淝'),
				'to' => 'féi'
			),
			array(
				'from' => array('菲', '匪', '诽', '斐', '蜚', '翡', '悱', '篚', '榧'),
				'to' => 'fěi'
			),
			array(
				'from' => array('费', '废', '沸', '肺', '吠', '痱', '狒', '镄', '芾'),
				'to' => 'fèi'
			),
			array(
				'from' => array('分', '纷', '氛', '芬', '吩', '酚', '玢'),
				'to' => 'fēn'
			),
			array(
				'from' => array('坟', '焚', '汾', '棼', '鼢'),
				'to' => 'fén'
			),
			array(
				'from' => array('粉'),
				'to' => 'fěn'
			),
			array(
				'from' => array('分', '份', '奋', '愤', '粪', '忿', '偾', '瀵', '鲼'),
				'to' => 'fèn'
			),
			array(
				'from' => array('风', '封', '丰', '峰', '疯', '锋', '蜂', '枫', '烽', '酆', '葑', '沣', '砜'),
				'to' => 'fēng'
			),
			array(
				'from' => array('逢', '缝', '冯'),
				'to' => 'féng'
			),
			array(
				'from' => array('讽', '唪'),
				'to' => 'fěng'
			),
			array(
				'from' => array('奉', '缝', '凤', '俸', '葑'),
				'to' => 'fèng'
			),
			array(
				'from' => array('佛'),
				'to' => 'fó'
			),
			array(
				'from' => array('否', '缶'),
				'to' => 'fǒu'
			),
			array(
				'from' => array('夫', '肤', '敷', '孵', '呋', '稃', '麸', '趺', '跗'),
				'to' => 'fū'
			),
			array(
				'from' => array('夫', '服', '福', '佛', '幅', '伏', '符', '浮', '扶', '弗', '拂', '袱', '俘', '芙', '孚', '匐', '辐', '涪', '氟', '桴', '蜉', '苻', '茯', '莩', '菔', '幞', '怫', '艴', '郛', '绂', '绋', '凫', '祓', '砩', '黻', '罘', '稃', '蚨', '芾', '蝠'),
				'to' => 'fú'
			),
			array(
				'from' => array('府', '父', '腐', '抚', '辅', '甫', '俯', '斧', '脯', '釜', '腑', '拊', '滏', '黼'),
				'to' => 'fǔ'
			),
			array(
				'from' => array('服', '复', '父', '负', '副', '富', '付', '妇', '附', '赴', '腹', '覆', '赋', '傅', '缚', '咐', '阜', '讣', '驸', '赙', '馥', '蝮', '鲋', '鳆'),
				'to' => 'fù'
			),
			array(
				'from' => array('咐'),
				'to' => 'fu'
			),
			array(
				'from' => array('夹', '咖', '嘎', '胳', '伽', '旮'),
				'to' => 'gā'
			),
			array(
				'from' => array('嘎', '噶', '轧', '尜', '钆'),
				'to' => 'gá'
			),
			array(
				'from' => array('嘎', '尕'),
				'to' => 'gǎ'
			),
			array(
				'from' => array('尬'),
				'to' => 'gà'
			),
			array(
				'from' => array('该', '赅', '垓', '陔'),
				'to' => 'gāi'
			),
			array(
				'from' => array('改'),
				'to' => 'gǎi'
			),
			array(
				'from' => array('概', '盖', '丐', '钙', '芥', '溉', '戤'),
				'to' => 'gài'
			),
			array(
				'from' => array('干', '甘', '肝', '杆', '尴', '乾', '竿', '坩', '苷', '柑', '泔', '矸', '疳', '酐'),
				'to' => 'gān'
			),
			array(
				'from' => array('感', '敢', '赶', '杆', '橄', '秆', '擀', '澉'),
				'to' => 'gǎn'
			),
			array(
				'from' => array('干', '赣', '淦', '绀', '旰'),
				'to' => 'gàn'
			),
			array(
				'from' => array('刚', '钢', '纲', '缸', '扛', '杠', '冈', '肛', '罡'),
				'to' => 'gāng'
			),
			array(
				'from' => array('港', '岗'),
				'to' => 'gǎng'
			),
			array(
				'from' => array('钢', '杠', '戆', '筻'),
				'to' => 'gàng'
			),
			array(
				'from' => array('高', '糕', '膏', '皋', '羔', '睾', '篙', '槔'),
				'to' => 'gāo'
			),
			array(
				'from' => array('稿', '搞', '藁', '槁', '缟', '镐', '杲'),
				'to' => 'gǎo'
			),
			array(
				'from' => array('告', '膏', '诰', '郜', '锆'),
				'to' => 'gào'
			),
			array(
				'from' => array('歌', '格', '哥', '戈', '割', '胳', '搁', '疙', '咯', '鸽', '屹', '仡', '圪', '纥', '袼'),
				'to' => 'gē'
			),
			array(
				'from' => array('革', '格', '隔', '葛', '阁', '胳', '搁', '蛤', '嗝', '骼', '颌', '搿', '膈', '镉', '塥', '鬲'),
				'to' => 'gé'
			),
			array(
				'from' => array('个', '各', '合', '盖', '葛', '哿', '舸'),
				'to' => 'gě'
			),
			array(
				'from' => array('个', '各', '铬', '硌', '虼'),
				'to' => 'gè'
			),
			array(
				'from' => array('给'),
				'to' => 'gěi'
			),
			array(
				'from' => array('根', '跟'),
				'to' => 'gēn'
			),
			array(
				'from' => array('哏'),
				'to' => 'gén'
			),
			array(
				'from' => array('艮'),
				'to' => 'gěn'
			),
			array(
				'from' => array('亘', '艮', '茛'),
				'to' => 'gèn'
			),
			array(
				'from' => array('更', '耕', '庚', '羹', '赓'),
				'to' => 'gēng'
			),
			array(
				'from' => array('耿', '颈', '梗', '哽', '鲠', '埂', '绠'),
				'to' => 'gěng'
			),
			array(
				'from' => array('更'),
				'to' => 'gèng'
			),
			array(
				'from' => array('工', '公', '共', '红', '供', '功', '攻', '宫', '恭', '躬', '龚', '弓', '肱', '蚣', '觥'),
				'to' => 'gōng'
			),
			array(
				'from' => array('巩', '拱', '汞', '珙'),
				'to' => 'gǒng'
			),
			array(
				'from' => array('共', '供', '贡'),
				'to' => 'gòng'
			),
			array(
				'from' => array('句', '沟', '勾', '钩', '篝', '佝', '枸', '缑', '鞲'),
				'to' => 'gōu'
			),
			array(
				'from' => array('狗', '苟', '岣', '枸', '笱'),
				'to' => 'gǒu'
			),
			array(
				'from' => array('够', '购', '构', '勾', '觏', '垢', '诟', '媾', '遘', '彀'),
				'to' => 'gòu'
			),
			array(
				'from' => array('姑', '骨', '孤', '估', '辜', '咕', '呱', '箍', '沽', '菇', '轱', '鸪', '毂', '菰', '蛄', '酤', '觚'),
				'to' => 'gū'
			),
			array(
				'from' => array('骨'),
				'to' => 'gú'
			),
			array(
				'from' => array('古', '股', '鼓', '骨', '谷', '贾', '汩', '蛊', '毂', '鹄', '牯', '臌', '诂', '瞽', '罟', '钴', '嘏', '蛄', '鹘'),
				'to' => 'gǔ'
			),
			array(
				'from' => array('故', '顾', '固', '估', '雇', '锢', '梏', '牿', '崮', '痼', '鲴'),
				'to' => 'gù'
			),
			array(
				'from' => array('括', '瓜', '刮', '呱', '栝', '胍', '鸹'),
				'to' => 'guā'
			),
			array(
				'from' => array('寡', '呱', '剐'),
				'to' => 'guǎ'
			),
			array(
				'from' => array('挂', '褂', '卦', '诖'),
				'to' => 'guà'
			),
			array(
				'from' => array('乖', '掴'),
				'to' => 'guāi'
			),
			array(
				'from' => array('拐'),
				'to' => 'guǎi'
			),
			array(
				'from' => array('怪'),
				'to' => 'guài'
			),
			array(
				'from' => array('关', '观', '官', '冠', '棺', '矜', '莞', '倌', '纶', '鳏'),
				'to' => 'guān'
			),
			array(
				'from' => array('管', '馆', '莞'),
				'to' => 'guǎn'
			),
			array(
				'from' => array('观', '惯', '冠', '贯', '罐', '灌', '掼', '盥', '涫', '鹳'),
				'to' => 'guàn'
			),
			array(
				'from' => array('光', '咣', '胱', '桄'),
				'to' => 'guāng'
			),
			array(
				'from' => array('广', '犷'),
				'to' => 'guǎng'
			),
			array(
				'from' => array('逛', '桄'),
				'to' => 'guàng'
			),
			array(
				'from' => array('规', '归', '瑰', '龟', '硅', '闺', '皈', '傀', '圭', '妫', '鲑'),
				'to' => 'guī'
			),
			array(
				'from' => array('鬼', '轨', '诡', '癸', '匦', '庋', '宄', '晷', '簋'),
				'to' => 'guǐ'
			),
			array(
				'from' => array('贵', '桂', '跪', '柜', '刽', '炔', '刿', '桧', '炅', '鳜'),
				'to' => 'guì'
			),
			array(
				'from' => array('滚', '鲧', '衮', '绲', '磙', '辊'),
				'to' => 'gǔn'
			),
			array(
				'from' => array('棍'),
				'to' => 'gùn'
			),
			array(
				'from' => array('过', '锅', '郭', '涡', '聒', '蝈', '崞', '埚', '呙'),
				'to' => 'guō'
			),
			array(
				'from' => array('国', '帼', '掴', '馘', '虢'),
				'to' => 'guó'
			),
			array(
				'from' => array('果', '裹', '猓', '椁', '蜾'),
				'to' => 'guǒ'
			),
			array(
				'from' => array('过'),
				'to' => 'guò'
			),
			array(
				'from' => array('哈', '铪'),
				'to' => 'hā'
			),
			array(
				'from' => array('虾', '蛤'),
				'to' => 'há'
			),
			array(
				'from' => array('哈'),
				'to' => 'hǎ'
			),
			array(
				'from' => array('哈'),
				'to' => 'hà'
			),
			array(
				'from' => array('嘿', '咳', '嗨'),
				'to' => 'hāi'
			),
			array(
				'from' => array('还', '孩', '骸'),
				'to' => 'hái'
			),
			array(
				'from' => array('海', '胲', '醢'),
				'to' => 'hǎi'
			),
			array(
				'from' => array('害', '亥', '骇', '氦'),
				'to' => 'hài'
			),
			array(
				'from' => array('酣', '憨', '顸', '鼾', '蚶'),
				'to' => 'hān'
			),
			array(
				'from' => array('含', '寒', '汗', '韩', '涵', '函', '晗', '焓', '邯', '邗'),
				'to' => 'hán'
			),
			array(
				'from' => array('喊', '罕', '阚'),
				'to' => 'hǎn'
			),
			array(
				'from' => array('汉', '汗', '憾', '翰', '撼', '旱', '捍', '悍', '瀚', '焊', '颔', '菡', '撖'),
				'to' => 'hàn'
			),
			array(
				'from' => array('夯'),
				'to' => 'hāng'
			),
			array(
				'from' => array('行', '航', '吭', '杭', '绗', '珩', '颃'),
				'to' => 'háng'
			),
			array(
				'from' => array('行', '巷', '沆'),
				'to' => 'hàng'
			),
			array(
				'from' => array('蒿', '薅', '嚆'),
				'to' => 'hāo'
			),
			array(
				'from' => array('号', '毫', '豪', '嚎', '壕', '貉', '嗥', '濠', '蚝'),
				'to' => 'háo'
			),
			array(
				'from' => array('好', '郝'),
				'to' => 'hǎo'
			),
			array(
				'from' => array('好', '号', '浩', '耗', '皓', '昊', '灏', '镐', '颢'),
				'to' => 'hào'
			),
			array(
				'from' => array('喝', '呵', '诃', '嗬'),
				'to' => 'hē'
			),
			array(
				'from' => array('和', '何', '合', '河', '核', '盒', '禾', '荷', '阂', '涸', '阖', '貉', '曷', '颌', '劾', '菏', '盍', '纥', '蚵', '翮'),
				'to' => 'hé'
			),
			array(
				'from' => array('和', '何', '喝', '赫', '吓', '贺', '荷', '鹤', '壑', '褐'),
				'to' => 'hè'
			),
			array(
				'from' => array('黑', '嘿', '嗨'),
				'to' => 'hēi'
			),
			array(
				'from' => array('痕'),
				'to' => 'hén'
			),
			array(
				'from' => array('很', '狠'),
				'to' => 'hěn'
			),
			array(
				'from' => array('恨'),
				'to' => 'hèn'
			),
			array(
				'from' => array('哼', '亨'),
				'to' => 'hēng'
			),
			array(
				'from' => array('行', '横', '衡', '恒', '蘅', '珩', '桁'),
				'to' => 'héng'
			),
			array(
				'from' => array('横'),
				'to' => 'hèng'
			),
			array(
				'from' => array('哼'),
				'to' => 'heng'
			),
			array(
				'from' => array('轰', '哄', '烘', '薨', '訇'),
				'to' => 'hōng'
			),
			array(
				'from' => array('红', '洪', '鸿', '宏', '虹', '弘', '泓', '闳', '蕻', '黉', '荭'),
				'to' => 'hóng'
			),
			array(
				'from' => array('哄'),
				'to' => 'hǒng'
			),
			array(
				'from' => array('哄', '讧', '蕻'),
				'to' => 'hòng'
			),
			array(
				'from' => array('侯', '喉', '猴', '瘊', '篌', '糇', '骺'),
				'to' => 'hóu'
			),
			array(
				'from' => array('吼'),
				'to' => 'hǒu'
			),
			array(
				'from' => array('后', '候', '後', '厚', '侯', '逅', '堠', '鲎'),
				'to' => 'hòu'
			),
			array(
				'from' => array('乎', '呼', '戏', '忽', '糊', '惚', '唿', '滹', '轷', '烀'),
				'to' => 'hū'
			),
			array(
				'from' => array('和', '胡', '湖', '糊', '核', '壶', '狐', '葫', '弧', '蝴', '囫', '瑚', '斛', '鹄', '醐', '猢', '槲', '鹕', '觳', '煳', '鹘'),
				'to' => 'hú'
			),
			array(
				'from' => array('虎', '浒', '唬', '琥'),
				'to' => 'hǔ'
			),
			array(
				'from' => array('护', '户', '互', '糊', '虎', '沪', '祜', '扈', '戽', '笏', '岵', '怙', '瓠', '鹱', '冱'),
				'to' => 'hù'
			),
			array(
				'from' => array('华', '化', '花', '哗', '砉'),
				'to' => 'huā'
			),
			array(
				'from' => array('华', '划', '滑', '哗', '豁', '猾', '骅', '铧'),
				'to' => 'huá'
			),
			array(
				'from' => array('话', '华', '化', '划', '画', '桦'),
				'to' => 'huà'
			),
			array(
				'from' => array('怀', '徊', '淮', '槐', '踝'),
				'to' => 'huái'
			),
			array(
				'from' => array('坏'),
				'to' => 'huài'
			),
			array(
				'from' => array('划'),
				'to' => 'hua'
			),
			array(
				'from' => array('欢', '獾'),
				'to' => 'huān'
			),
			array(
				'from' => array('还', '环', '寰', '鬟', '桓', '圜', '洹', '郇', '缳', '锾', '萑'),
				'to' => 'huán'
			),
			array(
				'from' => array('缓'),
				'to' => 'huǎn'
			),
			array(
				'from' => array('换', '患', '幻', '唤', '宦', '焕', '痪', '涣', '浣', '奂', '擐', '豢', '漶', '逭', '鲩'),
				'to' => 'huàn'
			),
			array(
				'from' => array('荒', '慌', '肓'),
				'to' => 'huāng'
			),
			array(
				'from' => array('黄', '皇', '煌', '惶', '徨', '璜', '簧', '凰', '潢', '蝗', '蟥', '遑', '隍', '磺', '癀', '湟', '篁', '鳇'),
				'to' => 'huáng'
			),
			array(
				'from' => array('晃', '恍', '谎', '幌'),
				'to' => 'huǎng'
			),
			array(
				'from' => array('晃'),
				'to' => 'huàng'
			),
			array(
				'from' => array('挥', '辉', '灰', '恢', '徽', '堕', '诙', '晖', '麾', '珲', '咴', '虺', '隳'),
				'to' => 'huī'
			),
			array(
				'from' => array('回', '徊', '蛔', '茴', '洄'),
				'to' => 'huí'
			),
			array(
				'from' => array('毁', '悔', '虺'),
				'to' => 'huǐ'
			),
			array(
				'from' => array('会', '汇', '惠', '慧', '溃', '绘', '讳', '贿', '晦', '秽', '诲', '彗', '烩', '荟', '卉', '喙', '恚', '浍', '哕', '缋', '桧', '蕙', '蟪'),
				'to' => 'huì'
			),
			array(
				'from' => array('婚', '昏', '荤', '阍'),
				'to' => 'hūn'
			),
			array(
				'from' => array('混', '魂', '浑', '馄', '珲'),
				'to' => 'hún'
			),
			array(
				'from' => array('混', '诨', '溷'),
				'to' => 'hùn'
			),
			array(
				'from' => array('豁', '劐', '攉', '锪', '耠'),
				'to' => 'huō'
			),
			array(
				'from' => array('和', '活'),
				'to' => 'huó'
			),
			array(
				'from' => array('火', '伙', '夥', '钬'),
				'to' => 'huǒ'
			),
			array(
				'from' => array('和', '或', '获', '货', '祸', '惑', '霍', '豁', '藿', '嚯', '镬', '蠖'),
				'to' => 'huò'
			),
			array(
				'from' => array('其', '几', '期', '机', '基', '击', '奇', '激', '积', '鸡', '迹', '绩', '饥', '缉', '圾', '姬', '矶', '肌', '讥', '叽', '稽', '畸', '跻', '羁', '嵇', '唧', '畿', '齑', '箕', '屐', '剞', '玑', '赍', '犄', '墼', '芨', '丌', '咭', '笄', '乩'),
				'to' => 'jī'
			),
			array(
				'from' => array('革', '及', '即', '辑', '级', '极', '集', '急', '籍', '吉', '疾', '嫉', '藉', '脊', '棘', '汲', '岌', '笈', '瘠', '诘', '亟', '楫', '蒺', '殛', '佶', '戢', '嵴', '蕺'),
				'to' => 'jí'
			),
			array(
				'from' => array('几', '给', '己', '革', '济', '纪', '挤', '脊', '戟', '虮', '掎', '麂'),
				'to' => 'jǐ'
			),
			array(
				'from' => array('记', '系', '计', '济', '寄', '际', '技', '纪', '继', '既', '齐', '季', '寂', '祭', '忌', '剂', '冀', '妓', '骥', '蓟', '悸', '伎', '暨', '霁', '稷', '偈', '鲫', '髻', '觊', '荠', '跽', '哜', '鲚', '洎', '芰'),
				'to' => 'jì'
			),
			array(
				'from' => array('家', '加', '佳', '夹', '嘉', '茄', '挟', '枷', '珈', '迦', '伽', '浃', '痂', '笳', '葭', '镓', '袈', '跏'),
				'to' => 'jiā'
			),
			array(
				'from' => array('夹', '颊', '戛', '荚', '郏', '恝', '铗', '袷', '蛱'),
				'to' => 'jiá'
			),
			array(
				'from' => array('假', '角', '脚', '甲', '搅', '贾', '缴', '绞', '饺', '矫', '佼', '狡', '剿', '侥', '皎', '胛', '铰', '挢', '岬', '徼', '湫', '敫', '钾', '嘏', '瘕'),
				'to' => 'jiǎ'
			),
			array(
				'from' => array('价', '假', '架', '驾', '嫁', '稼'),
				'to' => 'jià'
			),
			array(
				'from' => array('家'),
				'to' => 'jia'
			),
			array(
				'from' => array('间', '坚', '监', '渐', '兼', '艰', '肩', '浅', '尖', '奸', '溅', '煎', '歼', '缄', '笺', '菅', '蒹', '搛', '湔', '缣', '戋', '犍', '鹣', '鲣', '鞯'),
				'to' => 'jiān'
			),
			array(
				'from' => array('简', '减', '检', '剪', '捡', '拣', '俭', '碱', '茧', '柬', '蹇', '謇', '硷', '睑', '锏', '枧', '戬', '谫', '囝', '裥', '笕', '翦', '趼'),
				'to' => 'jiǎn'
			),
			array(
				'from' => array('见', '间', '件', '建', '监', '渐', '健', '剑', '键', '荐', '鉴', '践', '舰', '箭', '贱', '溅', '槛', '谏', '僭', '涧', '饯', '毽', '锏', '楗', '腱', '牮', '踺'),
				'to' => 'jiàn'
			),
			array(
				'from' => array('将', '江', '疆', '姜', '浆', '僵', '缰', '茳', '礓', '豇'),
				'to' => 'jiāng'
			),
			array(
				'from' => array('讲', '奖', '蒋', '桨', '耩'),
				'to' => 'jiǎng'
			),
			array(
				'from' => array('将', '强', '降', '酱', '浆', '虹', '匠', '犟', '绛', '洚', '糨'),
				'to' => 'jiàng'
			),
			array(
				'from' => array('教', '交', '焦', '骄', '郊', '胶', '椒', '娇', '浇', '姣', '跤', '蕉', '礁', '鲛', '僬', '鹪', '蛟', '艽', '茭'),
				'to' => 'jiāo'
			),
			array(
				'from' => array('嚼', '矫', '峤'),
				'to' => 'jiáo'
			),
			array(
				'from' => array('角', '脚', '搅', '缴', '绞', '饺', '矫', '佼', '狡', '剿', '侥', '皎', '挢', '徼', '湫', '敫', '铰'),
				'to' => 'jiǎo'
			),
			array(
				'from' => array('教', '觉', '校', '叫', '较', '轿', '嚼', '窖', '酵', '噍', '峤', '徼', '醮'),
				'to' => 'jiào'
			),
			array(
				'from' => array('接', '结', '节', '街', '阶', '皆', '揭', '楷', '嗟', '秸', '疖', '喈'),
				'to' => 'jiē'
			),
			array(
				'from' => array('结', '节', '杰', '捷', '截', '洁', '劫', '竭', '睫', '桔', '拮', '孑', '诘', '桀', '碣', '偈', '颉', '讦', '婕', '羯', '鲒'),
				'to' => 'jié'
			),
			array(
				'from' => array('解', '姐'),
				'to' => 'jiě'
			),
			array(
				'from' => array('界', '解', '价', '介', '借', '戒', '届', '藉', '诫', '芥', '疥', '蚧', '骱'),
				'to' => 'jiè'
			),
			array(
				'from' => array('家', '价'),
				'to' => 'jie'
			),
			array(
				'from' => array('今', '金', '禁', '津', '斤', '筋', '巾', '襟', '矜', '衿'),
				'to' => 'jīn'
			),
			array(
				'from' => array('尽', '仅', '紧', '谨', '锦', '瑾', '馑', '卺', '廑', '堇', '槿'),
				'to' => 'jǐn'
			),
			array(
				'from' => array('进', '近', '尽', '仅', '禁', '劲', '晋', '浸', '靳', '缙', '烬', '噤', '觐', '荩', '赆', '妗'),
				'to' => 'jìn'
			),
			array(
				'from' => array('经', '京', '精', '惊', '睛', '晶', '荆', '兢', '鲸', '泾', '旌', '茎', '腈', '菁', '粳'),
				'to' => 'jīng'
			),
			array(
				'from' => array('警', '景', '井', '颈', '憬', '阱', '儆', '刭', '肼'),
				'to' => 'jǐng'
			),
			array(
				'from' => array('经', '境', '竟', '静', '敬', '镜', '劲', '竞', '净', '径', '靖', '痉', '迳', '胫', '弪', '婧', '獍', '靓'),
				'to' => 'jìng'
			),
			array(
				'from' => array('扃'),
				'to' => 'jiōng'
			),
			array(
				'from' => array('窘', '炯', '迥', '炅'),
				'to' => 'jiǒng'
			),
			array(
				'from' => array('究', '纠', '揪', '鸠', '赳', '啾', '阄', '鬏'),
				'to' => 'jiū'
			),
			array(
				'from' => array('九', '酒', '久', '韭', '灸', '玖'),
				'to' => 'jiǔ'
			),
			array(
				'from' => array('就', '旧', '救', '疚', '舅', '咎', '臼', '鹫', '僦', '厩', '桕', '柩'),
				'to' => 'jiù'
			),
			array(
				'from' => array('车', '据', '且', '居', '俱', '拘', '驹', '鞠', '锯', '趄', '掬', '疽', '裾', '苴', '椐', '锔', '狙', '琚', '雎', '鞫'),
				'to' => 'jū'
			),
			array(
				'from' => array('局', '菊', '桔', '橘', '锔'),
				'to' => 'jú'
			),
			array(
				'from' => array('举', '柜', '矩', '咀', '沮', '踽', '龃', '榉', '莒', '枸'),
				'to' => 'jǔ'
			),
			array(
				'from' => array('据', '句', '具', '剧', '巨', '聚', '拒', '距', '俱', '惧', '沮', '瞿', '锯', '炬', '趄', '飓', '踞', '遽', '倨', '钜', '犋', '屦', '榘', '窭', '讵', '醵', '苣'),
				'to' => 'jù'
			),
			array(
				'from' => array('捐', '圈', '娟', '鹃', '涓', '镌', '蠲'),
				'to' => 'juān'
			),
			array(
				'from' => array('卷', '锩'),
				'to' => 'juǎn'
			),
			array(
				'from' => array('圈', '卷', '俊', '倦', '眷', '隽', '绢', '狷', '桊', '鄄'),
				'to' => 'juàn'
			),
			array(
				'from' => array('嗟', '撅', '噘'),
				'to' => 'juē'
			),
			array(
				'from' => array('觉', '绝', '决', '角', '脚', '嚼', '掘', '诀', '崛', '爵', '抉', '倔', '獗', '厥', '蹶', '攫', '谲', '矍', '孓', '橛', '噱', '珏', '桷', '劂', '爝', '镢', '蕨', '觖'),
				'to' => 'jué'
			),
			array(
				'from' => array('蹶'),
				'to' => 'juě'
			),
			array(
				'from' => array('倔'),
				'to' => 'juè'
			),
			array(
				'from' => array('军', '均', '君', '钧', '筠', '龟', '菌', '皲', '麇'),
				'to' => 'jūn'
			),
			array(
				'from' => array('俊', '峻', '隽', '菌', '郡', '骏', '竣', '捃', '浚'),
				'to' => 'jùn'
			),
			array(
				'from' => array('咖', '喀', '咔'),
				'to' => 'kā'
			),
			array(
				'from' => array('卡', '咯', '咔', '佧', '胩'),
				'to' => 'kǎ'
			),
			array(
				'from' => array('开', '揩', '锎'),
				'to' => 'kāi'
			),
			array(
				'from' => array('慨', '凯', '铠', '楷', '恺', '蒈', '剀', '垲', '锴'),
				'to' => 'kǎi'
			),
			array(
				'from' => array('忾'),
				'to' => 'kài'
			),
			array(
				'from' => array('看', '刊', '堪', '勘', '龛', '戡'),
				'to' => 'kān'
			),
			array(
				'from' => array('侃', '砍', '坎', '槛', '阚', '莰'),
				'to' => 'kǎn'
			),
			array(
				'from' => array('看', '嵌', '瞰', '阚'),
				'to' => 'kàn'
			),
			array(
				'from' => array('康', '慷', '糠', '闶'),
				'to' => 'kāng'
			),
			array(
				'from' => array('扛'),
				'to' => 'káng'
			),
			array(
				'from' => array('抗', '炕', '亢', '伉', '闶', '钪'),
				'to' => 'kàng'
			),
			array(
				'from' => array('尻'),
				'to' => 'kāo'
			),
			array(
				'from' => array('考', '烤', '拷', '栲'),
				'to' => 'kǎo'
			),
			array(
				'from' => array('靠', '铐', '犒'),
				'to' => 'kào'
			),
			array(
				'from' => array('科', '颗', '柯', '呵', '棵', '苛', '磕', '坷', '嗑', '瞌', '轲', '稞', '疴', '蝌', '钶', '窠', '颏', '珂', '髁'),
				'to' => 'kē'
			),
			array(
				'from' => array('咳', '壳', '颏'),
				'to' => 'ké'
			),
			array(
				'from' => array('可', '渴', '坷', '轲', '岢'),
				'to' => 'kě'
			),
			array(
				'from' => array('可', '克', '客', '刻', '课', '恪', '嗑', '溘', '骒', '缂', '氪', '锞', '蚵'),
				'to' => 'kè'
			),
			array(
				'from' => array('肯', '恳', '啃', '垦', '龈'),
				'to' => 'kěn'
			),
			array(
				'from' => array('裉'),
				'to' => 'kèn'
			),
			array(
				'from' => array('坑', '吭', '铿'),
				'to' => 'kēng'
			),
			array(
				'from' => array('空', '倥', '崆', '箜'),
				'to' => 'kōng'
			),
			array(
				'from' => array('恐', '孔', '倥'),
				'to' => 'kǒng'
			),
			array(
				'from' => array('空', '控'),
				'to' => 'kòng'
			),
			array(
				'from' => array('抠', '芤', '眍'),
				'to' => 'kōu'
			),
			array(
				'from' => array('口'),
				'to' => 'kǒu'
			),
			array(
				'from' => array('扣', '寇', '叩', '蔻', '筘'),
				'to' => 'kòu'
			),
			array(
				'from' => array('哭', '枯', '窟', '骷', '刳', '堀'),
				'to' => 'kū'
			),
			array(
				'from' => array('苦'),
				'to' => 'kǔ'
			),
			array(
				'from' => array('库', '裤', '酷', '喾', '绔'),
				'to' => 'kù'
			),
			array(
				'from' => array('夸'),
				'to' => 'kuā'
			),
			array(
				'from' => array('垮', '侉'),
				'to' => 'kuǎ'
			),
			array(
				'from' => array('跨', '挎', '胯'),
				'to' => 'kuà'
			),
			array(
				'from' => array('蒯'),
				'to' => 'kuǎi'
			),
			array(
				'from' => array('会', '快', '块', '筷', '脍', '哙', '侩', '狯', '浍', '郐'),
				'to' => 'kuài'
			),
			array(
				'from' => array('宽', '髋'),
				'to' => 'kuān'
			),
			array(
				'from' => array('款'),
				'to' => 'kuǎn'
			),
			array(
				'from' => array('框', '筐', '匡', '哐', '诓'),
				'to' => 'kuāng'
			),
			array(
				'from' => array('狂', '诳'),
				'to' => 'kuáng'
			),
			array(
				'from' => array('夼'),
				'to' => 'kuǎng'
			),
			array(
				'from' => array('况', '矿', '框', '旷', '眶', '邝', '圹', '纩', '贶'),
				'to' => 'kuàng'
			),
			array(
				'from' => array('亏', '窥', '盔', '岿', '悝'),
				'to' => 'kuī'
			),
			array(
				'from' => array('魁', '睽', '逵', '葵', '奎', '馗', '夔', '喹', '隗', '暌', '揆', '蝰'),
				'to' => 'kuí'
			),
			array(
				'from' => array('傀', '跬'),
				'to' => 'kuǐ'
			),
			array(
				'from' => array('愧', '溃', '馈', '匮', '喟', '聩', '篑', '蒉', '愦'),
				'to' => 'kuì'
			),
			array(
				'from' => array('昆', '坤', '鲲', '锟', '醌', '琨', '髡'),
				'to' => 'kūn'
			),
			array(
				'from' => array('捆', '悃', '阃'),
				'to' => 'kǔn'
			),
			array(
				'from' => array('困'),
				'to' => 'kùn'
			),
			array(
				'from' => array('括', '适', '阔', '扩', '廓', '栝', '蛞'),
				'to' => 'kuò'
			),
			array(
				'from' => array('拉', '啦', '喇', '垃', '邋'),
				'to' => 'lā'
			),
			array(
				'from' => array('拉', '喇', '旯', '砬'),
				'to' => 'lá'
			),
			array(
				'from' => array('拉', '喇'),
				'to' => 'lǎ'
			),
			array(
				'from' => array('落', '拉', '辣', '腊', '蜡', '剌', '瘌'),
				'to' => 'là'
			),
			array(
				'from' => array('蓝', '啦'),
				'to' => 'la'
			),
			array(
				'from' => array('来', '莱', '徕', '涞', '崃', '铼'),
				'to' => 'lái'
			),
			array(
				'from' => array('赖', '睐', '癞', '籁', '赉', '濑'),
				'to' => 'lài'
			),
			array(
				'from' => array('兰', '蓝', '栏', '拦', '篮', '澜', '婪', '岚', '斓', '阑', '褴', '镧', '谰'),
				'to' => 'lán'
			),
			array(
				'from' => array('懒', '览', '揽', '榄', '缆', '漤', '罱'),
				'to' => 'lǎn'
			),
			array(
				'from' => array('烂', '滥'),
				'to' => 'làn'
			),
			array(
				'from' => array('啷'),
				'to' => 'lāng'
			),
			array(
				'from' => array('狼', '郎', '廊', '琅', '螂', '榔', '锒', '稂', '阆'),
				'to' => 'láng'
			),
			array(
				'from' => array('朗'),
				'to' => 'lǎng'
			),
			array(
				'from' => array('浪', '郎', '莨', '蒗', '阆'),
				'to' => 'làng'
			),
			array(
				'from' => array('捞'),
				'to' => 'lāo'
			),
			array(
				'from' => array('劳', '牢', '唠', '崂', '铹', '痨', '醪'),
				'to' => 'láo'
			),
			array(
				'from' => array('老', '姥', '佬', '潦', '栳', '铑'),
				'to' => 'lǎo'
			),
			array(
				'from' => array('落', '络', '唠', '烙', '酪', '涝', '耢'),
				'to' => 'lào'
			),
			array(
				'from' => array('肋'),
				'to' => 'lē'
			),
			array(
				'from' => array('乐', '勒', '仂', '叻', '泐', '鳓'),
				'to' => 'lè'
			),
			array(
				'from' => array('了'),
				'to' => 'le'
			),
			array(
				'from' => array('勒', '擂'),
				'to' => 'lēi'
			),
			array(
				'from' => array('累', '雷', '擂', '羸', '镭', '嫘', '缧', '檑'),
				'to' => 'léi'
			),
			array(
				'from' => array('累', '蕾', '垒', '磊', '儡', '诔', '耒'),
				'to' => 'lěi'
			),
			array(
				'from' => array('类', '泪', '累', '擂', '肋', '酹'),
				'to' => 'lèi'
			),
			array(
				'from' => array('嘞'),
				'to' => 'lei'
			),
			array(
				'from' => array('棱'),
				'to' => 'lēng'
			),
			array(
				'from' => array('楞', '棱', '塄'),
				'to' => 'léng'
			),
			array(
				'from' => array('冷'),
				'to' => 'lěng'
			),
			array(
				'from' => array('愣'),
				'to' => 'lèng'
			),
			array(
				'from' => array('哩'),
				'to' => 'lī'
			),
			array(
				'from' => array('离', '丽', '黎', '璃', '漓', '狸', '梨', '篱', '犁', '厘', '罹', '藜', '骊', '蜊', '黧', '缡', '喱', '鹂', '嫠', '蠡', '鲡', '蓠'),
				'to' => 'lí'
			),
			array(
				'from' => array('里', '理', '李', '礼', '哩', '鲤', '俚', '逦', '娌', '悝', '澧', '锂', '蠡', '醴', '鳢'),
				'to' => 'lǐ'
			),
			array(
				'from' => array('力', '利', '立', '历', '例', '丽', '励', '厉', '莉', '笠', '粒', '俐', '栗', '隶', '吏', '沥', '雳', '莅', '戾', '俪', '砺', '痢', '郦', '詈', '荔', '枥', '呖', '唳', '猁', '溧', '砾', '栎', '轹', '傈', '坜', '苈', '疠', '疬', '蛎', '鬲', '篥', '粝', '跞', '藓'),
				'to' => 'lì'
			),
			array(
				'from' => array('璃', '哩'),
				'to' => 'li'
			),
			array(
				'from' => array('俩'),
				'to' => 'liǎ'
			),
			array(
				'from' => array('联', '连', '怜', '莲', '廉', '帘', '涟', '镰', '裢', '濂', '臁', '奁', '蠊', '鲢'),
				'to' => 'lián'
			),
			array(
				'from' => array('脸', '敛', '琏', '蔹', '裣'),
				'to' => 'liǎn'
			),
			array(
				'from' => array('练', '恋', '炼', '链', '殓', '楝', '潋'),
				'to' => 'liàn'
			),
			array(
				'from' => array('量', '良', '梁', '凉', '粮', '粱', '踉', '莨', '椋', '墚'),
				'to' => 'liáng'
			),
			array(
				'from' => array('两', '俩', '魉'),
				'to' => 'liǎng'
			),
			array(
				'from' => array('量', '亮', '辆', '凉', '谅', '晾', '踉', '靓'),
				'to' => 'liàng'
			),
			array(
				'from' => array('撩', '撂'),
				'to' => 'liāo'
			),
			array(
				'from' => array('聊', '疗', '辽', '僚', '寥', '撩', '撂', '缭', '寮', '燎', '嘹', '獠', '鹩'),
				'to' => 'liáo'
			),
			array(
				'from' => array('了', '潦', '燎', '蓼', '钌'),
				'to' => 'liǎo'
			),
			array(
				'from' => array('了', '料', '廖', '镣', '撩', '撂', '尥', '钌'),
				'to' => 'liào'
			),
			array(
				'from' => array('咧'),
				'to' => 'liē'
			),
			array(
				'from' => array('裂', '咧'),
				'to' => 'liě'
			),
			array(
				'from' => array('列', '烈', '裂', '劣', '猎', '趔', '冽', '洌', '捩', '埒', '躐', '鬣'),
				'to' => 'liè'
			),
			array(
				'from' => array('咧'),
				'to' => 'lie'
			),
			array(
				'from' => array('林', '临', '秘', '邻', '琳', '淋', '霖', '麟', '鳞', '磷', '嶙', '辚', '粼', '遴', '啉', '瞵'),
				'to' => 'lín'
			),
			array(
				'from' => array('凛', '懔', '檩', '廪'),
				'to' => 'lǐn'
			),
			array(
				'from' => array('淋', '吝', '躏', '赁', '蔺', '膦'),
				'to' => 'lìn'
			),
			array(
				'from' => array('拎'),
				'to' => 'līng'
			),
			array(
				'from' => array('令', '灵', '零', '龄', '凌', '玲', '铃', '陵', '伶', '聆', '囹', '棱', '菱', '苓', '翎', '棂', '瓴', '绫', '酃', '泠', '羚', '蛉', '柃', '鲮'),
				'to' => 'líng'
			),
			array(
				'from' => array('领', '令', '岭'),
				'to' => 'lǐng'
			),
			array(
				'from' => array('令', '另', '呤'),
				'to' => 'lìng'
			),
			array(
				'from' => array('溜', '熘'),
				'to' => 'liū'
			),
			array(
				'from' => array('留', '流', '刘', '瘤', '榴', '浏', '硫', '琉', '遛', '馏', '镏', '旒', '骝', '鎏'),
				'to' => 'liú'
			),
			array(
				'from' => array('柳', '绺', '锍'),
				'to' => 'liǔ'
			),
			array(
				'from' => array('六', '陆', '溜', '碌', '遛', '馏', '镏', '鹨'),
				'to' => 'liù'
			),
			array(
				'from' => array('咯'),
				'to' => 'lo'
			),
			array(
				'from' => array('隆'),
				'to' => 'lōng'
			),
			array(
				'from' => array('龙', '隆', '笼', '胧', '咙', '聋', '珑', '窿', '茏', '栊', '泷', '砻', '癃'),
				'to' => 'lóng'
			),
			array(
				'from' => array('笼', '拢', '垄', '陇', '垅'),
				'to' => 'lǒng'
			),
			array(
				'from' => array('弄'),
				'to' => 'lòng'
			),
			array(
				'from' => array('搂'),
				'to' => 'lōu'
			),
			array(
				'from' => array('楼', '喽', '偻', '娄', '髅', '蝼', '蒌', '耧'),
				'to' => 'lóu'
			),
			array(
				'from' => array('搂', '篓', '嵝'),
				'to' => 'lǒu'
			),
			array(
				'from' => array('露', '陋', '漏', '镂', '瘘'),
				'to' => 'lòu'
			),
			array(
				'from' => array('喽'),
				'to' => 'lou'
			),
			array(
				'from' => array('噜', '撸'),
				'to' => 'lū'
			),
			array(
				'from' => array('卢', '炉', '庐', '芦', '颅', '泸', '轳', '鲈', '垆', '胪', '鸬', '舻', '栌'),
				'to' => 'lú'
			),
			array(
				'from' => array('鲁', '芦', '卤', '虏', '掳', '橹', '镥'),
				'to' => 'lǔ'
			),
			array(
				'from' => array('六', '路', '陆', '录', '露', '绿', '鹿', '碌', '禄', '辘', '麓', '赂', '漉', '戮', '簏', '鹭', '潞', '璐', '辂', '渌', '蓼', '逯'),
				'to' => 'lù'
			),
			array(
				'from' => array('轳', '氇'),
				'to' => 'lu'
			),
			array(
				'from' => array('旅', '履', '屡', '侣', '缕', '吕', '捋', '铝', '偻', '褛', '膂', '稆'),
				'to' => 'lǚ'
			),
			array(
				'from' => array('律', '绿', '率', '虑', '滤', '氯'),
				'to' => 'lǜ'
			),
			array(
				'from' => array('驴', '榈', '闾'),
				'to' => 'lv'
			),
			array(
				'from' => array('峦', '挛', '孪', '栾', '銮', '滦', '鸾', '娈', '脔'),
				'to' => 'luán'
			),
			array(
				'from' => array('卵'),
				'to' => 'luǎn'
			),
			array(
				'from' => array('乱'),
				'to' => 'luàn'
			),
			array(
				'from' => array('抡'),
				'to' => 'lūn'
			),
			array(
				'from' => array('论', '轮', '伦', '沦', '仑', '抡', '囵', '纶'),
				'to' => 'lún'
			),
			array(
				'from' => array('论'),
				'to' => 'lùn'
			),
			array(
				'from' => array('落', '罗', '捋'),
				'to' => 'luō'
			),
			array(
				'from' => array('罗', '逻', '萝', '螺', '锣', '箩', '骡', '猡', '椤', '脶', '镙'),
				'to' => 'luó'
			),
			array(
				'from' => array('裸', '倮', '蠃', '瘰'),
				'to' => 'luǒ'
			),
			array(
				'from' => array('落', '络', '洛', '骆', '咯', '摞', '烙', '珞', '泺', '漯', '荦', '硌', '雒'),
				'to' => 'luò'
			),
			array(
				'from' => array('罗'),
				'to' => 'luo'
			),
			array(
				'from' => array('呒'),
				'to' => 'm2'
			),
			array(
				'from' => array('妈', '麻', '摩', '抹', '蚂', '嬷'),
				'to' => 'mā'
			),
			array(
				'from' => array('吗', '麻', '蟆'),
				'to' => 'má'
			),
			array(
				'from' => array('马', '吗', '码', '玛', '蚂', '犸'),
				'to' => 'mǎ'
			),
			array(
				'from' => array('骂', '蚂', '唛', '杩'),
				'to' => 'mà'
			),
			array(
				'from' => array('么', '吗', '嘛'),
				'to' => 'ma'
			),
			array(
				'from' => array('埋', '霾'),
				'to' => 'mái'
			),
			array(
				'from' => array('买', '荬'),
				'to' => 'mǎi'
			),
			array(
				'from' => array('卖', '麦', '迈', '脉', '劢'),
				'to' => 'mài'
			),
			array(
				'from' => array('颟'),
				'to' => 'mān'
			),
			array(
				'from' => array('埋', '蛮', '馒', '瞒', '蔓', '谩', '鳗', '鞔'),
				'to' => 'mán'
			),
			array(
				'from' => array('满', '螨'),
				'to' => 'mǎn'
			),
			array(
				'from' => array('慢', '漫', '曼', '蔓', '谩', '墁', '幔', '缦', '熳', '镘'),
				'to' => 'màn'
			),
			array(
				'from' => array('忙', '茫', '盲', '芒', '氓', '邙', '硭'),
				'to' => 'máng'
			),
			array(
				'from' => array('莽', '蟒', '漭'),
				'to' => 'mǎng'
			),
			array(
				'from' => array('猫'),
				'to' => 'māo'
			),
			array(
				'from' => array('毛', '猫', '矛', '茅', '髦', '锚', '牦', '旄', '蝥', '蟊', '茆'),
				'to' => 'máo'
			),
			array(
				'from' => array('卯', '铆', '峁', '泖', '昴'),
				'to' => 'mǎo'
			),
			array(
				'from' => array('冒', '贸', '帽', '貌', '茂', '耄', '瑁', '懋', '袤', '瞀'),
				'to' => 'mào'
			),
			array(
				'from' => array('么', '麽'),
				'to' => 'me'
			),
			array(
				'from' => array('没', '眉', '梅', '媒', '枚', '煤', '霉', '玫', '糜', '酶', '莓', '嵋', '湄', '楣', '猸', '镅', '鹛'),
				'to' => 'méi'
			),
			array(
				'from' => array('美', '每', '镁', '浼'),
				'to' => 'měi'
			),
			array(
				'from' => array('妹', '魅', '昧', '谜', '媚', '寐', '袂'),
				'to' => 'mèi'
			),
			array(
				'from' => array('闷'),
				'to' => 'mēn'
			),
			array(
				'from' => array('门', '扪', '钔'),
				'to' => 'mén'
			),
			array(
				'from' => array('闷', '懑', '焖'),
				'to' => 'mèn'
			),
			array(
				'from' => array('们'),
				'to' => 'men'
			),
			array(
				'from' => array('蒙'),
				'to' => 'mēng'
			),
			array(
				'from' => array('蒙', '盟', '朦', '氓', '萌', '檬', '瞢', '甍', '礞', '虻', '艨'),
				'to' => 'méng'
			),
			array(
				'from' => array('蒙', '猛', '勐', '懵', '蠓', '蜢', '锰', '艋'),
				'to' => 'měng'
			),
			array(
				'from' => array('梦', '孟'),
				'to' => 'mèng'
			),
			array(
				'from' => array('眯', '咪'),
				'to' => 'mī'
			),
			array(
				'from' => array('迷', '弥', '谜', '靡', '糜', '醚', '麋', '猕', '祢', '縻', '蘼'),
				'to' => 'mí'
			),
			array(
				'from' => array('米', '眯', '靡', '弭', '敉', '脒', '芈'),
				'to' => 'mǐ'
			),
			array(
				'from' => array('密', '秘', '觅', '蜜', '谧', '泌', '汨', '宓', '幂', '嘧', '糸'),
				'to' => 'mì'
			),
			array(
				'from' => array('棉', '眠', '绵'),
				'to' => 'mián'
			),
			array(
				'from' => array('免', '缅', '勉', '腼', '冕', '娩', '渑', '湎', '沔', '眄', '黾'),
				'to' => 'miǎn'
			),
			array(
				'from' => array('面'),
				'to' => 'miàn'
			),
			array(
				'from' => array('喵'),
				'to' => 'miāo'
			),
			array(
				'from' => array('描', '苗', '瞄', '鹋'),
				'to' => 'miáo'
			),
			array(
				'from' => array('秒', '渺', '藐', '缈', '淼', '杪', '邈', '眇'),
				'to' => 'miǎo'
			),
			array(
				'from' => array('妙', '庙', '缪'),
				'to' => 'miào'
			),
			array(
				'from' => array('乜', '咩'),
				'to' => 'miē'
			),
			array(
				'from' => array('灭', '蔑', '篾', '蠛'),
				'to' => 'miè'
			),
			array(
				'from' => array('民', '珉', '岷', '缗', '玟', '苠'),
				'to' => 'mín'
			),
			array(
				'from' => array('敏', '悯', '闽', '泯', '皿', '抿', '闵', '愍', '黾', '鳘'),
				'to' => 'mǐn'
			),
			array(
				'from' => array('名', '明', '鸣', '盟', '铭', '冥', '茗', '溟', '瞑', '暝', '螟'),
				'to' => 'míng'
			),
			array(
				'from' => array('酩'),
				'to' => 'mǐng'
			),
			array(
				'from' => array('命'),
				'to' => 'mìng'
			),
			array(
				'from' => array('谬', '缪'),
				'to' => 'miù'
			),
			array(
				'from' => array('摸'),
				'to' => 'mō'
			),
			array(
				'from' => array('无', '模', '麽', '磨', '摸', '摩', '魔', '膜', '蘑', '馍', '摹', '谟', '嫫'),
				'to' => 'mó'
			),
			array(
				'from' => array('抹'),
				'to' => 'mǒ'
			),
			array(
				'from' => array('没', '万', '默', '莫', '末', '冒', '磨', '寞', '漠', '墨', '抹', '陌', '脉', '嘿', '沫', '蓦', '茉', '貉', '秣', '镆', '殁', '瘼', '耱', '貊', '貘'),
				'to' => 'mò'
			),
			array(
				'from' => array('哞'),
				'to' => 'mōu'
			),
			array(
				'from' => array('谋', '牟', '眸', '缪', '鍪', '蛑', '侔'),
				'to' => 'móu'
			),
			array(
				'from' => array('某'),
				'to' => 'mǒu'
			),
			array(
				'from' => array('模', '毪'),
				'to' => 'mú'
			),
			array(
				'from' => array('母', '姆', '姥', '亩', '拇', '牡'),
				'to' => 'mǔ'
			),
			array(
				'from' => array('目', '木', '幕', '慕', '牧', '墓', '募', '暮', '牟', '穆', '睦', '沐', '坶', '苜', '仫', '钼'),
				'to' => 'mù'
			),
			array(
				'from' => array('嗯', '唔'),
				'to' => 'n'
			),
			array(
				'from' => array('嗯', '唔'),
				'to' => 'n'
			),
			array(
				'from' => array('嗯'),
				'to' => 'n'
			),
			array(
				'from' => array('那', '南'),
				'to' => 'nā'
			),
			array(
				'from' => array('拿', '镎'),
				'to' => 'ná'
			),
			array(
				'from' => array('那', '哪'),
				'to' => 'nǎ'
			),
			array(
				'from' => array('那', '呢', '纳', '娜', '呐', '捺', '钠', '肭', '衲'),
				'to' => 'nà'
			),
			array(
				'from' => array('哪', '呐'),
				'to' => 'na'
			),
			array(
				'from' => array('哪', '乃', '奶', '氖', '艿'),
				'to' => 'nǎi'
			),
			array(
				'from' => array('奈', '耐', '鼐', '佴', '萘', '柰'),
				'to' => 'nài'
			),
			array(
				'from' => array('囝', '囡'),
				'to' => 'nān'
			),
			array(
				'from' => array('难', '南', '男', '楠', '喃'),
				'to' => 'nán'
			),
			array(
				'from' => array('腩', '蝻', '赧'),
				'to' => 'nǎn'
			),
			array(
				'from' => array('难'),
				'to' => 'nàn'
			),
			array(
				'from' => array('囊', '囔'),
				'to' => 'nāng'
			),
			array(
				'from' => array('囊', '馕'),
				'to' => 'náng'
			),
			array(
				'from' => array('馕', '攮', '曩'),
				'to' => 'nǎng'
			),
			array(
				'from' => array('孬'),
				'to' => 'nāo'
			),
			array(
				'from' => array('努', '挠', '呶', '猱', '铙', '硇', '蛲'),
				'to' => 'náo'
			),
			array(
				'from' => array('脑', '恼', '瑙', '垴'),
				'to' => 'nǎo'
			),
			array(
				'from' => array('闹', '淖'),
				'to' => 'nào'
			),
			array(
				'from' => array('哪'),
				'to' => 'né'
			),
			array(
				'from' => array('呢', '呐', '讷'),
				'to' => 'nè'
			),
			array(
				'from' => array('呢', '呐'),
				'to' => 'ne'
			),
			array(
				'from' => array('哪', '馁'),
				'to' => 'něi'
			),
			array(
				'from' => array('那', '内'),
				'to' => 'nèi'
			),
			array(
				'from' => array('嫩', '恁'),
				'to' => 'nèn'
			),
			array(
				'from' => array('能'),
				'to' => 'néng'
			),
			array(
				'from' => array('嗯', '唔'),
				'to' => 'ng2'
			),
			array(
				'from' => array('嗯', '唔'),
				'to' => 'ng3'
			),
			array(
				'from' => array('嗯'),
				'to' => 'ng4'
			),
			array(
				'from' => array('妮'),
				'to' => 'nī'
			),
			array(
				'from' => array('呢', '尼', '泥', '倪', '霓', '坭', '猊', '怩', '铌', '鲵'),
				'to' => 'ní'
			),
			array(
				'from' => array('你', '拟', '旎', '祢'),
				'to' => 'nǐ'
			),
			array(
				'from' => array('泥', '尿', '逆', '匿', '腻', '昵', '溺', '睨', '慝', '伲'),
				'to' => 'nì'
			),
			array(
				'from' => array('蔫', '拈'),
				'to' => 'niān'
			),
			array(
				'from' => array('年', '粘', '黏', '鲇', '鲶'),
				'to' => 'nián'
			),
			array(
				'from' => array('碾', '捻', '撵', '辇'),
				'to' => 'niǎn'
			),
			array(
				'from' => array('念', '廿', '酿', '埝'),
				'to' => 'niàn'
			),
			array(
				'from' => array('娘', '酿'),
				'to' => 'niáng'
			),
			array(
				'from' => array('酿'),
				'to' => 'niàng'
			),
			array(
				'from' => array('鸟', '袅', '嬲', '茑'),
				'to' => 'niǎo'
			),
			array(
				'from' => array('尿', '溺', '脲'),
				'to' => 'niào'
			),
			array(
				'from' => array('捏'),
				'to' => 'niē'
			),
			array(
				'from' => array('涅', '聂', '孽', '蹑', '嗫', '啮', '镊', '镍', '乜', '陧', '颞', '臬', '蘖'),
				'to' => 'niè'
			),
			array(
				'from' => array('您', '恁'),
				'to' => 'nín'
			),
			array(
				'from' => array('宁', '凝', '拧', '咛', '狞', '柠', '苎', '甯', '聍'),
				'to' => 'níng'
			),
			array(
				'from' => array('拧'),
				'to' => 'nǐng'
			),
			array(
				'from' => array('宁', '拧', '泞', '佞'),
				'to' => 'nìng'
			),
			array(
				'from' => array('妞'),
				'to' => 'niū'
			),
			array(
				'from' => array('牛'),
				'to' => 'niú'
			),
			array(
				'from' => array('纽', '扭', '钮', '狃', '忸'),
				'to' => 'niǔ'
			),
			array(
				'from' => array('拗'),
				'to' => 'niù'
			),
			array(
				'from' => array('农', '浓', '侬', '哝', '脓'),
				'to' => 'nóng'
			),
			array(
				'from' => array('弄'),
				'to' => 'nòng'
			),
			array(
				'from' => array('耨'),
				'to' => 'nòu'
			),
			array(
				'from' => array('奴', '孥', '驽'),
				'to' => 'nú'
			),
			array(
				'from' => array('努', '弩', '胬'),
				'to' => 'nǔ'
			),
			array(
				'from' => array('怒'),
				'to' => 'nù'
			),
			array(
				'from' => array('女', '钕'),
				'to' => 'nǚ'
			),
			array(
				'from' => array('恧', '衄'),
				'to' => 'nǜ'
			),
			array(
				'from' => array('暖'),
				'to' => 'nuǎn'
			),
			array(
				'from' => array('娜', '挪', '傩'),
				'to' => 'nuó'
			),
			array(
				'from' => array('诺', '懦', '糯', '喏', '搦', '锘'),
				'to' => 'nuò'
			),
			array(
				'from' => array('噢', '喔'),
				'to' => 'ō'
			),
			array(
				'from' => array('哦'),
				'to' => 'ó'
			),
			array(
				'from' => array('哦'),
				'to' => 'ò'
			),
			array(
				'from' => array('区', '欧', '殴', '鸥', '讴', '瓯', '沤'),
				'to' => 'ōu'
			),
			array(
				'from' => array('偶', '呕', '藕', '耦'),
				'to' => 'ǒu'
			),
			array(
				'from' => array('呕', '沤', '怄'),
				'to' => 'òu'
			),
			array(
				'from' => array('派', '扒', '趴', '啪', '葩'),
				'to' => 'pā'
			),
			array(
				'from' => array('爬', '扒', '耙', '杷', '钯', '筢'),
				'to' => 'pá'
			),
			array(
				'from' => array('怕', '帕'),
				'to' => 'pà'
			),
			array(
				'from' => array('琶'),
				'to' => 'pa'
			),
			array(
				'from' => array('拍'),
				'to' => 'pāi'
			),
			array(
				'from' => array('排', '牌', '徘', '俳'),
				'to' => 'pái'
			),
			array(
				'from' => array('排', '迫'),
				'to' => 'pǎi'
			),
			array(
				'from' => array('派', '湃', '蒎', '哌'),
				'to' => 'pài'
			),
			array(
				'from' => array('番', '攀', '潘', '扳'),
				'to' => 'pān'
			),
			array(
				'from' => array('般', '盘', '胖', '磐', '蹒', '爿', '蟠'),
				'to' => 'pán'
			),
			array(
				'from' => array('判', '盼', '叛', '畔', '拚', '襻', '袢', '泮'),
				'to' => 'pàn'
			),
			array(
				'from' => array('乓', '膀', '滂'),
				'to' => 'pāng'
			),
			array(
				'from' => array('旁', '庞', '膀', '磅', '彷', '螃', '逄'),
				'to' => 'páng'
			),
			array(
				'from' => array('耪'),
				'to' => 'pǎng'
			),
			array(
				'from' => array('胖'),
				'to' => 'pàng'
			),
			array(
				'from' => array('炮', '抛', '泡', '脬'),
				'to' => 'pāo'
			),
			array(
				'from' => array('跑', '炮', '袍', '刨', '咆', '狍', '匏', '庖'),
				'to' => 'páo'
			),
			array(
				'from' => array('跑'),
				'to' => 'pǎo'
			),
			array(
				'from' => array('炮', '泡', '疱'),
				'to' => 'pào'
			),
			array(
				'from' => array('呸', '胚', '醅'),
				'to' => 'pēi'
			),
			array(
				'from' => array('陪', '培', '赔', '裴', '锫'),
				'to' => 'péi'
			),
			array(
				'from' => array('配', '佩', '沛', '辔', '帔', '旆', '霈'),
				'to' => 'pèi'
			),
			array(
				'from' => array('喷'),
				'to' => 'pēn'
			),
			array(
				'from' => array('盆', '湓'),
				'to' => 'pén'
			),
			array(
				'from' => array('喷'),
				'to' => 'pèn'
			),
			array(
				'from' => array('烹', '抨', '砰', '澎', '怦', '嘭'),
				'to' => 'pēng'
			),
			array(
				'from' => array('朋', '鹏', '彭', '棚', '蓬', '膨', '篷', '澎', '硼', '堋', '蟛'),
				'to' => 'péng'
			),
			array(
				'from' => array('捧'),
				'to' => 'pěng'
			),
			array(
				'from' => array('碰'),
				'to' => 'pèng'
			),
			array(
				'from' => array('批', '坏', '披', '辟', '劈', '坯', '霹', '噼', '丕', '纰', '砒', '邳', '铍'),
				'to' => 'pī'
			),
			array(
				'from' => array('皮', '疲', '啤', '脾', '琵', '毗', '郫', '鼙', '裨', '埤', '陴', '芘', '枇', '罴', '铍', '陂', '蚍', '蜱', '貔'),
				'to' => 'pí'
			),
			array(
				'from' => array('否', '匹', '劈', '痞', '癖', '圮', '擗', '吡', '庀', '仳', '疋'),
				'to' => 'pǐ'
			),
			array(
				'from' => array('屁', '辟', '僻', '譬', '媲', '淠', '甓', '睥'),
				'to' => 'pì'
			),
			array(
				'from' => array('片', '篇', '偏', '翩', '扁', '犏'),
				'to' => 'piān'
			),
			array(
				'from' => array('便', '蹁', '缏', '胼', '骈'),
				'to' => 'pián'
			),
			array(
				'from' => array('谝'),
				'to' => 'piǎn'
			),
			array(
				'from' => array('片', '骗'),
				'to' => 'piàn'
			),
			array(
				'from' => array('漂', '飘', '剽', '缥', '螵'),
				'to' => 'piāo'
			),
			array(
				'from' => array('朴', '瓢', '嫖'),
				'to' => 'piáo'
			),
			array(
				'from' => array('漂', '瞟', '缥', '殍', '莩'),
				'to' => 'piǎo'
			),
			array(
				'from' => array('票', '漂', '骠', '嘌'),
				'to' => 'piào'
			),
			array(
				'from' => array('撇', '瞥', '氕'),
				'to' => 'piē'
			),
			array(
				'from' => array('撇', '丿', '苤'),
				'to' => 'piě'
			),
			array(
				'from' => array('拼', '拚', '姘'),
				'to' => 'pīn'
			),
			array(
				'from' => array('贫', '频', '苹', '嫔', '颦'),
				'to' => 'pín'
			),
			array(
				'from' => array('品', '榀'),
				'to' => 'pǐn'
			),
			array(
				'from' => array('聘', '牝'),
				'to' => 'pìn'
			),
			array(
				'from' => array('乒', '娉', '俜'),
				'to' => 'pīng'
			),
			array(
				'from' => array('平', '评', '瓶', '凭', '萍', '屏', '冯', '苹', '坪', '枰', '鲆'),
				'to' => 'píng'
			),
			array(
				'from' => array('颇', '坡', '泊', '朴', '泼', '陂', '泺', '攴', '钋'),
				'to' => 'pō'
			),
			array(
				'from' => array('繁', '婆', '鄱', '皤'),
				'to' => 'pó'
			),
			array(
				'from' => array('叵', '钷', '笸'),
				'to' => 'pǒ'
			),
			array(
				'from' => array('破', '迫', '朴', '魄', '粕', '珀'),
				'to' => 'pò'
			),
			array(
				'from' => array('剖'),
				'to' => 'pōu'
			),
			array(
				'from' => array('裒', '掊'),
				'to' => 'póu'
			),
			array(
				'from' => array('掊'),
				'to' => 'pǒu'
			),
			array(
				'from' => array('铺', '扑', '仆', '噗'),
				'to' => 'pū'
			),
			array(
				'from' => array('葡', '蒲', '仆', '脯', '菩', '匍', '璞', '濮', '莆', '镤'),
				'to' => 'pú'
			),
			array(
				'from' => array('普', '堡', '朴', '谱', '浦', '溥', '埔', '圃', '氆', '镨', '蹼'),
				'to' => 'pǔ'
			),
			array(
				'from' => array('暴', '铺', '堡', '曝', '瀑'),
				'to' => 'pù'
			),
			array(
				'from' => array('期', '七', '妻', '欺', '缉', '戚', '凄', '漆', '栖', '沏', '蹊', '嘁', '萋', '槭', '柒', '欹', '桤'),
				'to' => 'qī'
			),
			array(
				'from' => array('其', '奇', '棋', '齐', '旗', '骑', '歧', '琪', '祈', '脐', '祺', '祁', '崎', '琦', '淇', '岐', '荠', '俟', '耆', '芪', '颀', '圻', '骐', '畦', '亓', '萁', '蕲', '畦', '蛴', '蜞', '綦', '鳍', '麒'),
				'to' => 'qí'
			),
			array(
				'from' => array('起', '企', '启', '岂', '乞', '稽', '绮', '杞', '芑', '屺', '綮'),
				'to' => 'qǐ'
			),
			array(
				'from' => array('气', '妻', '器', '汽', '齐', '弃', '泣', '契', '迄', '砌', '憩', '汔', '亟', '讫', '葺', '碛'),
				'to' => 'qì'
			),
			array(
				'from' => array('掐', '伽', '葜', '袷'),
				'to' => 'qiā'
			),
			array(
				'from' => array('卡'),
				'to' => 'qiǎ'
			),
			array(
				'from' => array('恰', '洽', '髂'),
				'to' => 'qià'
			),
			array(
				'from' => array('千', '签', '牵', '迁', '谦', '铅', '骞', '悭', '芊', '愆', '阡', '仟', '岍', '扦', '佥', '搴', '褰', '钎'),
				'to' => 'qiān'
			),
			array(
				'from' => array('前', '钱', '潜', '乾', '虔', '钳', '掮', '黔', '荨', '钤', '犍', '箝', '鬈'),
				'to' => 'qián'
			),
			array(
				'from' => array('浅', '遣', '谴', '缱', '肷'),
				'to' => 'qiǎn'
			),
			array(
				'from' => array('欠', '歉', '纤', '嵌', '倩', '堑', '茜', '芡', '慊', '椠'),
				'to' => 'qiàn'
			),
			array(
				'from' => array('将', '枪', '抢', '腔', '呛', '锵', '跄', '羌', '戕', '戗', '镪', '蜣', '锖'),
				'to' => 'qiāng'
			),
			array(
				'from' => array('强', '墙', '蔷', '樯', '嫱'),
				'to' => 'qiáng'
			),
			array(
				'from' => array('强', '抢', '襁', '镪', '羟'),
				'to' => 'qiǎng'
			),
			array(
				'from' => array('呛', '跄', '炝', '戗'),
				'to' => 'qiàng'
			),
			array(
				'from' => array('悄', '敲', '雀', '锹', '跷', '橇', '缲', '硗', '劁'),
				'to' => 'qiāo'
			),
			array(
				'from' => array('桥', '乔', '侨', '瞧', '翘', '蕉', '憔', '樵', '峤', '谯', '荞', '鞒'),
				'to' => 'qiáo'
			),
			array(
				'from' => array('悄', '巧', '雀', '愀'),
				'to' => 'qiǎo'
			),
			array(
				'from' => array('翘', '俏', '窍', '壳', '峭', '撬', '鞘', '诮', '谯'),
				'to' => 'qiào'
			),
			array(
				'from' => array('切'),
				'to' => 'qiē'
			),
			array(
				'from' => array('茄', '伽'),
				'to' => 'qié'
			),
			array(
				'from' => array('且'),
				'to' => 'qiě'
			),
			array(
				'from' => array('切', '窃', '怯', '趄', '妾', '砌', '惬', '锲', '挈', '郄', '箧', '慊'),
				'to' => 'qiè'
			),
			array(
				'from' => array('亲', '钦', '侵', '衾'),
				'to' => 'qīn'
			),
			array(
				'from' => array('琴', '秦', '勤', '芹', '擒', '矜', '覃', '禽', '噙', '廑', '溱', '檎', '锓', '嗪', '芩', '螓'),
				'to' => 'qín'
			),
			array(
				'from' => array('寝'),
				'to' => 'qǐn'
			),
			array(
				'from' => array('沁', '揿', '吣'),
				'to' => 'qìn'
			),
			array(
				'from' => array('青', '清', '轻', '倾', '卿', '氢', '蜻', '圊', '鲭'),
				'to' => 'qīng'
			),
			array(
				'from' => array('情', '晴', '擎', '氰', '檠', '黥'),
				'to' => 'qíng'
			),
			array(
				'from' => array('请', '顷', '謦', '苘'),
				'to' => 'qǐng'
			),
			array(
				'from' => array('亲', '庆', '罄', '磬', '箐', '綮'),
				'to' => 'qìng'
			),
			array(
				'from' => array('穷', '琼', '穹', '茕', '邛', '蛩', '筇', '跫', '銎'),
				'to' => 'qióng'
			),
			array(
				'from' => array('秋', '邱', '丘', '龟', '蚯', '鳅', '楸', '湫'),
				'to' => 'qiū'
			),
			array(
				'from' => array('求', '球', '仇', '囚', '酋', '裘', '虬', '俅', '遒', '赇', '泅', '逑', '犰', '蝤', '巯', '鼽'),
				'to' => 'qiú'
			),
			array(
				'from' => array('糗'),
				'to' => 'qiǔ'
			),
			array(
				'from' => array('区', '曲', '屈', '趋', '驱', '躯', '觑', '岖', '蛐', '祛', '蛆', '麴', '诎', '黢'),
				'to' => 'qū'
			),
			array(
				'from' => array('渠', '瞿', '衢', '癯', '劬', '璩', '氍', '朐', '磲', '鸲', '蕖', '蠼', '蘧'),
				'to' => 'qú'
			),
			array(
				'from' => array('取', '曲', '娶', '龋', '苣'),
				'to' => 'qǔ'
			),
			array(
				'from' => array('去', '趣', '觑', '阒'),
				'to' => 'qù'
			),
			array(
				'from' => array('戌'),
				'to' => 'qu'
			),
			array(
				'from' => array('圈', '悛'),
				'to' => 'quān'
			),
			array(
				'from' => array('全', '权', '泉', '拳', '诠', '颧', '蜷', '荃', '铨', '痊', '醛', '辁', '筌', '鬈'),
				'to' => 'quán'
			),
			array(
				'from' => array('犬', '绻', '畎'),
				'to' => 'quǎn'
			),
			array(
				'from' => array('劝', '券'),
				'to' => 'quàn'
			),
			array(
				'from' => array('缺', '阙', '炔'),
				'to' => 'quē'
			),
			array(
				'from' => array('瘸'),
				'to' => 'qué'
			),
			array(
				'from' => array('却', '确', '雀', '榷', '鹊', '阕', '阙', '悫'),
				'to' => 'què'
			),
			array(
				'from' => array('逡'),
				'to' => 'qūn'
			),
			array(
				'from' => array('群', '裙', '麇'),
				'to' => 'qún'
			),
			array(
				'from' => array('然', '燃', '髯', '蚺'),
				'to' => 'rán'
			),
			array(
				'from' => array('染', '冉', '苒'),
				'to' => 'rǎn'
			),
			array(
				'from' => array('嚷'),
				'to' => 'rāng'
			),
			array(
				'from' => array('瓤', '禳', '穰'),
				'to' => 'ráng'
			),
			array(
				'from' => array('嚷', '攘', '壤', '禳'),
				'to' => 'rǎng'
			),
			array(
				'from' => array('让'),
				'to' => 'ràng'
			),
			array(
				'from' => array('饶', '娆', '桡', '荛'),
				'to' => 'ráo'
			),
			array(
				'from' => array('扰', '绕', '娆'),
				'to' => 'rǎo'
			),
			array(
				'from' => array('绕'),
				'to' => 'rào'
			),
			array(
				'from' => array('若', '惹', '喏'),
				'to' => 'rě'
			),
			array(
				'from' => array('热'),
				'to' => 'rè'
			),
			array(
				'from' => array('人', '任', '仁', '壬'),
				'to' => 'rén'
			),
			array(
				'from' => array('忍', '稔', '荏'),
				'to' => 'rěn'
			),
			array(
				'from' => array('任', '认', '韧', '刃', '纫', '饪', '仞', '葚', '妊', '轫', '衽'),
				'to' => 'rèn'
			),
			array(
				'from' => array('扔'),
				'to' => 'rēng'
			),
			array(
				'from' => array('仍'),
				'to' => 'réng'
			),
			array(
				'from' => array('日'),
				'to' => 'rì'
			),
			array(
				'from' => array('容', '荣', '融', '蓉', '溶', '绒', '熔', '榕', '戎', '嵘', '茸', '狨', '肜', '蝾'),
				'to' => 'róng'
			),
			array(
				'from' => array('冗'),
				'to' => 'rǒng'
			),
			array(
				'from' => array('柔', '揉', '蹂', '糅', '鞣'),
				'to' => 'róu'
			),
			array(
				'from' => array('肉'),
				'to' => 'ròu'
			),
			array(
				'from' => array('如', '儒', '茹', '嚅', '濡', '孺', '蠕', '薷', '铷', '襦', '颥'),
				'to' => 'rú'
			),
			array(
				'from' => array('辱', '乳', '汝'),
				'to' => 'rǔ'
			),
			array(
				'from' => array('入', '褥', '缛', '洳', '溽', '蓐'),
				'to' => 'rù'
			),
			array(
				'from' => array('软', '阮', '朊'),
				'to' => 'ruǎn'
			),
			array(
				'from' => array('蕤'),
				'to' => 'ruí'
			),
			array(
				'from' => array('蕊'),
				'to' => 'ruǐ'
			),
			array(
				'from' => array('瑞', '锐', '芮', '睿', '枘', '蚋'),
				'to' => 'ruì'
			),
			array(
				'from' => array('润', '闰'),
				'to' => 'rùn'
			),
			array(
				'from' => array('若', '弱', '偌', '箬'),
				'to' => 'ruò'
			),
			array(
				'from' => array('撒', '仨', '挲'),
				'to' => 'sā'
			),
			array(
				'from' => array('洒', '撒'),
				'to' => 'sǎ'
			),
			array(
				'from' => array('萨', '卅', '飒', '脎'),
				'to' => 'sà'
			),
			array(
				'from' => array('思', '塞', '腮', '鳃', '噻'),
				'to' => 'sāi'
			),
			array(
				'from' => array('赛', '塞'),
				'to' => 'sài'
			),
			array(
				'from' => array('三', '叁', '毵'),
				'to' => 'sān'
			),
			array(
				'from' => array('散', '伞', '馓', '糁', '霰'),
				'to' => 'sǎn'
			),
			array(
				'from' => array('散'),
				'to' => 'sàn'
			),
			array(
				'from' => array('丧', '桑'),
				'to' => 'sāng'
			),
			array(
				'from' => array('嗓', '搡', '磉', '颡'),
				'to' => 'sǎng'
			),
			array(
				'from' => array('丧'),
				'to' => 'sàng'
			),
			array(
				'from' => array('骚', '搔', '臊', '缲', '缫', '鳋'),
				'to' => 'sāo'
			),
			array(
				'from' => array('扫', '嫂'),
				'to' => 'sǎo'
			),
			array(
				'from' => array('扫', '梢', '臊', '埽', '瘙'),
				'to' => 'sào'
			),
			array(
				'from' => array('色', '塞', '涩', '瑟', '啬', '铯', '穑'),
				'to' => 'sè'
			),
			array(
				'from' => array('森'),
				'to' => 'sēn'
			),
			array(
				'from' => array('僧'),
				'to' => 'sēng'
			),
			array(
				'from' => array('杀', '沙', '刹', '纱', '杉', '莎', '煞', '砂', '挲', '鲨', '痧', '裟', '铩'),
				'to' => 'shā'
			),
			array(
				'from' => array('傻'),
				'to' => 'shǎ'
			),
			array(
				'from' => array('沙', '啥', '厦', '煞', '霎', '嗄', '歃', '唼'),
				'to' => 'shà'
			),
			array(
				'from' => array('筛', '酾'),
				'to' => 'shāi'
			),
			array(
				'from' => array('色'),
				'to' => 'shǎi'
			),
			array(
				'from' => array('晒'),
				'to' => 'shài'
			),
			array(
				'from' => array('山', '衫', '删', '煽', '扇', '珊', '杉', '栅', '跚', '姗', '潸', '膻', '芟', '埏', '钐', '舢', '苫', '髟'),
				'to' => 'shān'
			),
			array(
				'from' => array('闪', '陕', '掺', '掸'),
				'to' => 'shǎn'
			),
			array(
				'from' => array('单', '善', '扇', '禅', '擅', '膳', '讪', '汕', '赡', '缮', '嬗', '掸', '骟', '剡', '苫', '鄯', '钐', '疝', '蟮', '鳝'),
				'to' => 'shàn'
			),
			array(
				'from' => array('商', '伤', '汤', '殇', '觞', '熵', '墒'),
				'to' => 'shāng'
			),
			array(
				'from' => array('上', '赏', '晌', '垧'),
				'to' => 'shǎng'
			),
			array(
				'from' => array('上', '尚', '绱'),
				'to' => 'shàng'
			),
			array(
				'from' => array('裳'),
				'to' => 'shang'
			),
			array(
				'from' => array('烧', '稍', '梢', '捎', '鞘', '蛸', '筲', '艄'),
				'to' => 'shāo'
			),
			array(
				'from' => array('勺', '韶', '苕', '杓', '芍'),
				'to' => 'sháo'
			),
			array(
				'from' => array('少'),
				'to' => 'shǎo'
			),
			array(
				'from' => array('少', '绍', '召', '稍', '哨', '邵', '捎', '潲', '劭'),
				'to' => 'shào'
			),
			array(
				'from' => array('奢', '赊', '猞', '畲'),
				'to' => 'shē'
			),
			array(
				'from' => array('折', '舌', '蛇', '佘'),
				'to' => 'shé'
			),
			array(
				'from' => array('舍'),
				'to' => 'shě'
			),
			array(
				'from' => array('社', '设', '舍', '涉', '射', '摄', '赦', '慑', '麝', '滠', '歙', '厍'),
				'to' => 'shè'
			),
			array(
				'from' => array('谁'),
				'to' => 'shéi'
			),
			array(
				'from' => array('身', '深', '参', '申', '伸', '绅', '呻', '莘', '娠', '诜', '砷', '糁'),
				'to' => 'shēn'
			),
			array(
				'from' => array('什', '神', '甚'),
				'to' => 'shén'
			),
			array(
				'from' => array('审', '沈', '婶', '谂', '哂', '渖', '矧'),
				'to' => 'shěn'
			),
			array(
				'from' => array('甚', '慎', '渗', '肾', '蜃', '葚', '胂', '椹'),
				'to' => 'shèn'
			),
			array(
				'from' => array('生', '声', '胜', '升', '牲', '甥', '笙'),
				'to' => 'shēng'
			),
			array(
				'from' => array('绳', '渑'),
				'to' => 'shéng'
			),
			array(
				'from' => array('省', '眚'),
				'to' => 'shěng'
			),
			array(
				'from' => array('胜', '圣', '盛', '乘', '剩', '嵊', '晟'),
				'to' => 'shèng'
			),
			array(
				'from' => array('师', '诗', '失', '施', '尸', '湿', '狮', '嘘', '虱', '蓍', '酾', '鲺'),
				'to' => 'shī'
			),
			array(
				'from' => array('时', '十', '实', '什', '识', '食', '石', '拾', '蚀', '埘', '莳', '炻', '鲥'),
				'to' => 'shí'
			),
			array(
				'from' => array('使', '始', '史', '驶', '屎', '矢', '豕'),
				'to' => 'shǐ'
			),
			array(
				'from' => array('是', '事', '世', '市', '士', '式', '视', '似', '示', '室', '势', '试', '释', '适', '氏', '饰', '逝', '誓', '嗜', '侍', '峙', '仕', '恃', '柿', '轼', '拭', '噬', '弑', '谥', '莳', '贳', '铈', '螫', '舐', '筮'),
				'to' => 'shì'
			),
			array(
				'from' => array('殖', '匙'),
				'to' => 'shi'
			),
			array(
				'from' => array('收'),
				'to' => 'shōu'
			),
			array(
				'from' => array('熟'),
				'to' => 'shóu'
			),
			array(
				'from' => array('手', '首', '守', '艏'),
				'to' => 'shǒu'
			),
			array(
				'from' => array('受', '授', '售', '瘦', '寿', '兽', '狩', '绶'),
				'to' => 'shòu'
			),
			array(
				'from' => array('书', '输', '殊', '舒', '叔', '疏', '抒', '淑', '梳', '枢', '蔬', '倏', '菽', '摅', '姝', '纾', '毹', '殳', '疋'),
				'to' => 'shū'
			),
			array(
				'from' => array('熟', '孰', '赎', '塾', '秫'),
				'to' => 'shú'
			),
			array(
				'from' => array('数', '属', '署', '鼠', '薯', '暑', '蜀', '黍', '曙'),
				'to' => 'shǔ'
			),
			array(
				'from' => array('数', '术', '树', '述', '束', '竖', '恕', '墅', '漱', '俞', '戍', '庶', '澍', '沭', '丨', '腧'),
				'to' => 'shù'
			),
			array(
				'from' => array('刷', '唰'),
				'to' => 'shuā'
			),
			array(
				'from' => array('耍'),
				'to' => 'shuǎ'
			),
			array(
				'from' => array('刷'),
				'to' => 'shuà'
			),
			array(
				'from' => array('衰', '摔'),
				'to' => 'shuāi'
			),
			array(
				'from' => array('甩'),
				'to' => 'shuǎi'
			),
			array(
				'from' => array('率', '帅', '蟀'),
				'to' => 'shuài'
			),
			array(
				'from' => array('栓', '拴', '闩'),
				'to' => 'shuān'
			),
			array(
				'from' => array('涮'),
				'to' => 'shuàn'
			),
			array(
				'from' => array('双', '霜', '孀', '泷'),
				'to' => 'shuāng'
			),
			array(
				'from' => array('爽'),
				'to' => 'shuǎng'
			),
			array(
				'from' => array('谁'),
				'to' => 'shuí'
			),
			array(
				'from' => array('水'),
				'to' => 'shuǐ'
			),
			array(
				'from' => array('说', '税', '睡'),
				'to' => 'shuì'
			),
			array(
				'from' => array('吮'),
				'to' => 'shǔn'
			),
			array(
				'from' => array('顺', '舜', '瞬'),
				'to' => 'shùn'
			),
			array(
				'from' => array('说'),
				'to' => 'shuō'
			),
			array(
				'from' => array('数', '朔', '硕', '烁', '铄', '妁', '蒴', '槊', '搠'),
				'to' => 'shuò'
			),
			array(
				'from' => array('思', '斯', '司', '私', '丝', '撕', '厮', '嘶', '鸶', '咝', '澌', '缌', '锶', '厶', '蛳'),
				'to' => 'sī'
			),
			array(
				'from' => array('死'),
				'to' => 'sǐ'
			),
			array(
				'from' => array('四', '似', '食', '寺', '肆', '伺', '饲', '嗣', '巳', '祀', '驷', '泗', '俟', '汜', '兕', '姒', '耜', '笥'),
				'to' => 'sì'
			),
			array(
				'from' => array('厕'),
				'to' => 'si'
			),
			array(
				'from' => array('松', '忪', '淞', '崧', '嵩', '凇', '菘'),
				'to' => 'sōng'
			),
			array(
				'from' => array('耸', '悚', '怂', '竦'),
				'to' => 'sǒng'
			),
			array(
				'from' => array('送', '宋', '诵', '颂', '讼'),
				'to' => 'sòng'
			),
			array(
				'from' => array('搜', '艘', '馊', '嗖', '溲', '飕', '锼', '螋'),
				'to' => 'sōu'
			),
			array(
				'from' => array('擞', '叟', '薮', '嗾', '瞍'),
				'to' => 'sǒu'
			),
			array(
				'from' => array('嗽', '擞'),
				'to' => 'sòu'
			),
			array(
				'from' => array('苏', '稣', '酥'),
				'to' => 'sū'
			),
			array(
				'from' => array('俗'),
				'to' => 'sú'
			),
			array(
				'from' => array('诉', '速', '素', '肃', '宿', '缩', '塑', '溯', '粟', '簌', '夙', '嗉', '谡', '僳', '愫', '涑', '蔌', '觫'),
				'to' => 'sù'
			),
			array(
				'from' => array('酸', '狻'),
				'to' => 'suān'
			),
			array(
				'from' => array('算', '蒜'),
				'to' => 'suàn'
			),
			array(
				'from' => array('虽', '尿', '荽', '睢', '眭', '濉'),
				'to' => 'suī'
			),
			array(
				'from' => array('随', '遂', '隋', '绥'),
				'to' => 'suí'
			),
			array(
				'from' => array('髓'),
				'to' => 'suǐ'
			),
			array(
				'from' => array('岁', '碎', '遂', '祟', '隧', '邃', '穗', '燧', '谇'),
				'to' => 'suì'
			),
			array(
				'from' => array('孙', '荪', '狲', '飧'),
				'to' => 'sūn'
			),
			array(
				'from' => array('损', '笋', '榫', '隼'),
				'to' => 'sǔn'
			),
			array(
				'from' => array('缩', '莎', '梭', '嗦', '唆', '挲', '娑', '睃', '桫', '嗍', '蓑', '羧'),
				'to' => 'suō'
			),
			array(
				'from' => array('所', '索', '锁', '琐', '唢'),
				'to' => 'suǒ'
			),
			array(
				'from' => array('他', '她', '它', '踏', '塌', '遢', '溻', '铊', '趿'),
				'to' => 'tā'
			),
			array(
				'from' => array('塔', '鳎', '獭'),
				'to' => 'tǎ'
			),
			array(
				'from' => array('踏', '拓', '榻', '嗒', '蹋', '沓', '挞', '闼', '漯'),
				'to' => 'tà'
			),
			array(
				'from' => array('台', '胎', '苔'),
				'to' => 'tāi'
			),
			array(
				'from' => array('台', '抬', '苔', '邰', '薹', '骀', '炱', '跆', '鲐'),
				'to' => 'tái'
			),
			array(
				'from' => array('呔'),
				'to' => 'tǎi'
			),
			array(
				'from' => array('太', '态', '泰', '汰', '酞', '肽', '钛'),
				'to' => 'tài'
			),
			array(
				'from' => array('摊', '贪', '滩', '瘫', '坍'),
				'to' => 'tān'
			),
			array(
				'from' => array('谈', '弹', '坛', '谭', '潭', '覃', '痰', '澹', '檀', '昙', '锬', '镡', '郯'),
				'to' => 'tán'
			),
			array(
				'from' => array('坦', '毯', '忐', '袒', '钽'),
				'to' => 'tǎn'
			),
			array(
				'from' => array('探', '叹', '炭', '碳'),
				'to' => 'tàn'
			),
			array(
				'from' => array('汤', '趟', '铴', '镗', '耥', '羰'),
				'to' => 'tāng'
			),
			array(
				'from' => array('堂', '唐', '糖', '膛', '塘', '棠', '搪', '溏', '螳', '瑭', '樘', '镗', '螗', '饧', '醣'),
				'to' => 'táng'
			),
			array(
				'from' => array('躺', '倘', '淌', '傥', '帑'),
				'to' => 'tǎng'
			),
			array(
				'from' => array('趟', '烫'),
				'to' => 'tàng'
			),
			array(
				'from' => array('涛', '掏', '滔', '叨', '焘', '韬', '饕', '绦'),
				'to' => 'tāo'
			),
			array(
				'from' => array('逃', '陶', '桃', '淘', '萄', '啕', '洮', '鼗'),
				'to' => 'táo'
			),
			array(
				'from' => array('讨'),
				'to' => 'tǎo'
			),
			array(
				'from' => array('套'),
				'to' => 'tào'
			),
			array(
				'from' => array('特', '忑', '忒', '慝', '铽'),
				'to' => 'tè'
			),
			array(
				'from' => array('忒'),
				'to' => 'tēi'
			),
			array(
				'from' => array('腾', '疼', '藤', '誊', '滕'),
				'to' => 'téng'
			),
			array(
				'from' => array('体', '踢', '梯', '剔', '锑'),
				'to' => 'tī'
			),
			array(
				'from' => array('提', '题', '啼', '蹄', '醍', '绨', '缇', '鹈', '荑'),
				'to' => 'tí'
			),
			array(
				'from' => array('体'),
				'to' => 'tǐ'
			),
			array(
				'from' => array('替', '涕', '剃', '惕', '屉', '嚏', '悌', '倜', '逖', '绨', '裼'),
				'to' => 'tì'
			),
			array(
				'from' => array('天', '添'),
				'to' => 'tiān'
			),
			array(
				'from' => array('田', '填', '甜', '恬', '佃', '阗', '畋', '钿'),
				'to' => 'tián'
			),
			array(
				'from' => array('腆', '舔', '忝', '殄'),
				'to' => 'tiǎn'
			),
			array(
				'from' => array('掭'),
				'to' => 'tiàn'
			),
			array(
				'from' => array('挑', '佻', '祧'),
				'to' => 'tiāo'
			),
			array(
				'from' => array('条', '调', '迢', '鲦', '苕', '髫', '龆', '蜩', '笤'),
				'to' => 'tiáo'
			),
			array(
				'from' => array('挑', '窕'),
				'to' => 'tiǎo'
			),
			array(
				'from' => array('跳', '眺', '粜'),
				'to' => 'tiào'
			),
			array(
				'from' => array('贴', '帖', '萜'),
				'to' => 'tiē'
			),
			array(
				'from' => array('铁', '帖'),
				'to' => 'tiě'
			),
			array(
				'from' => array('帖', '餮'),
				'to' => 'tiè'
			),
			array(
				'from' => array('听', '厅', '汀', '烃'),
				'to' => 'tīng'
			),
			array(
				'from' => array('停', '庭', '亭', '婷', '廷', '霆', '蜓', '葶', '莛'),
				'to' => 'tíng'
			),
			array(
				'from' => array('挺', '艇', '町', '铤', '梃'),
				'to' => 'tǐng'
			),
			array(
				'from' => array('梃'),
				'to' => 'tìng'
			),
			array(
				'from' => array('通', '恫', '嗵'),
				'to' => 'tōng'
			),
			array(
				'from' => array('同', '童', '彤', '铜', '桐', '瞳', '佟', '酮', '侗', '仝', '垌', '茼', '峒', '潼', '砼'),
				'to' => 'tóng'
			),
			array(
				'from' => array('统', '筒', '桶', '捅', '侗'),
				'to' => 'tǒng'
			),
			array(
				'from' => array('同', '通', '痛', '恸'),
				'to' => 'tòng'
			),
			array(
				'from' => array('偷'),
				'to' => 'tōu'
			),
			array(
				'from' => array('头', '投', '骰'),
				'to' => 'tóu'
			),
			array(
				'from' => array('钭'),
				'to' => 'tǒu'
			),
			array(
				'from' => array('透'),
				'to' => 'tòu'
			),
			array(
				'from' => array('突', '秃', '凸'),
				'to' => 'tū'
			),
			array(
				'from' => array('图', '途', '徒', '屠', '涂', '荼', '菟', '酴'),
				'to' => 'tú'
			),
			array(
				'from' => array('土', '吐', '钍'),
				'to' => 'tǔ'
			),
			array(
				'from' => array('吐', '兔', '堍', '菟'),
				'to' => 'tù'
			),
			array(
				'from' => array('湍'),
				'to' => 'tuān'
			),
			array(
				'from' => array('团', '抟'),
				'to' => 'tuán'
			),
			array(
				'from' => array('疃'),
				'to' => 'tuǎn'
			),
			array(
				'from' => array('彖'),
				'to' => 'tuàn'
			),
			array(
				'from' => array('推', '忒'),
				'to' => 'tuī'
			),
			array(
				'from' => array('颓'),
				'to' => 'tuí'
			),
			array(
				'from' => array('腿'),
				'to' => 'tuǐ'
			),
			array(
				'from' => array('退', '褪', '蜕', '煺'),
				'to' => 'tuì'
			),
			array(
				'from' => array('吞', '暾'),
				'to' => 'tūn'
			),
			array(
				'from' => array('屯', '饨', '臀', '囤', '豚'),
				'to' => 'tún'
			),
			array(
				'from' => array('氽'),
				'to' => 'tǔn'
			),
			array(
				'from' => array('褪'),
				'to' => 'tùn'
			),
			array(
				'from' => array('托', '脱', '拖', '乇'),
				'to' => 'tuō'
			),
			array(
				'from' => array('陀', '舵', '驼', '砣', '驮', '沱', '跎', '坨', '鸵', '橐', '佗', '铊', '酡', '柁', '鼍'),
				'to' => 'tuó'
			),
			array(
				'from' => array('妥', '椭', '庹'),
				'to' => 'tuǒ'
			),
			array(
				'from' => array('魄', '拓', '唾', '柝', '箨'),
				'to' => 'tuò'
			),
			array(
				'from' => array('挖', '哇', '凹', '娲', '蛙', '洼'),
				'to' => 'wā'
			),
			array(
				'from' => array('娃'),
				'to' => 'wá'
			),
			array(
				'from' => array('瓦', '佤'),
				'to' => 'wǎ'
			),
			array(
				'from' => array('瓦', '袜', '腽'),
				'to' => 'wà'
			),
			array(
				'from' => array('哇'),
				'to' => 'wa'
			),
			array(
				'from' => array('歪'),
				'to' => 'wāi'
			),
			array(
				'from' => array('崴'),
				'to' => 'wǎi'
			),
			array(
				'from' => array('外'),
				'to' => 'wài'
			),
			array(
				'from' => array('湾', '弯', '蜿', '剜', '豌'),
				'to' => 'wān'
			),
			array(
				'from' => array('完', '玩', '顽', '丸', '纨', '芄', '烷'),
				'to' => 'wán'
			),
			array(
				'from' => array('晚', '碗', '挽', '婉', '惋', '宛', '莞', '娩', '畹', '皖', '绾', '琬', '脘', '菀'),
				'to' => 'wǎn'
			),
			array(
				'from' => array('万', '腕', '蔓'),
				'to' => 'wàn'
			),
			array(
				'from' => array('汪', '尢'),
				'to' => 'wāng'
			),
			array(
				'from' => array('王', '忘', '亡', '芒'),
				'to' => 'wáng'
			),
			array(
				'from' => array('往', '网', '枉', '惘', '罔', '辋', '魍'),
				'to' => 'wǎng'
			),
			array(
				'from' => array('望', '王', '往', '忘', '旺', '妄'),
				'to' => 'wàng'
			),
			array(
				'from' => array('委', '威', '微', '危', '巍', '萎', '偎', '薇', '逶', '煨', '崴', '葳', '隈'),
				'to' => 'wēi'
			),
			array(
				'from' => array('为', '维', '围', '唯', '违', '韦', '惟', '帷', '帏', '圩', '囗', '潍', '桅', '嵬', '闱', '沩', '涠'),
				'to' => 'wéi'
			),
			array(
				'from' => array('委', '伟', '唯', '尾', '玮', '伪', '炜', '纬', '萎', '娓', '苇', '猥', '痿', '韪', '洧', '隗', '诿', '艉', '鲔'),
				'to' => 'wěi'
			),
			array(
				'from' => array('为', '位', '未', '味', '卫', '谓', '遗', '慰', '魏', '蔚', '畏', '胃', '喂', '尉', '渭', '猬', '軎'),
				'to' => 'wèi'
			),
			array(
				'from' => array('温', '瘟'),
				'to' => 'wēn'
			),
			array(
				'from' => array('文', '闻', '纹', '蚊', '雯', '璺', '阌'),
				'to' => 'wén'
			),
			array(
				'from' => array('稳', '吻', '紊', '刎'),
				'to' => 'wěn'
			),
			array(
				'from' => array('问', '纹', '汶', '璺'),
				'to' => 'wèn'
			),
			array(
				'from' => array('翁', '嗡'),
				'to' => 'wēng'
			),
			array(
				'from' => array('蓊'),
				'to' => 'wěng'
			),
			array(
				'from' => array('瓮', '蕹'),
				'to' => 'wèng'
			),
			array(
				'from' => array('窝', '涡', '蜗', '喔', '倭', '挝', '莴'),
				'to' => 'wō'
			),
			array(
				'from' => array('哦'),
				'to' => 'wó'
			),
			array(
				'from' => array('我'),
				'to' => 'wǒ'
			),
			array(
				'from' => array('握', '卧', '哦', '渥', '沃', '斡', '幄', '肟', '硪', '龌'),
				'to' => 'wò'
			),
			array(
				'from' => array('於', '恶', '屋', '污', '乌', '巫', '呜', '诬', '兀', '钨', '邬', '圬'),
				'to' => 'wū'
			),
			array(
				'from' => array('无', '亡', '吴', '吾', '捂', '毋', '梧', '唔', '芜', '浯', '蜈', '鼯'),
				'to' => 'wú'
			),
			array(
				'from' => array('五', '武', '午', '舞', '伍', '侮', '捂', '妩', '忤', '鹉', '牾', '迕', '庑', '怃', '仵'),
				'to' => 'wǔ'
			),
			array(
				'from' => array('物', '务', '误', '恶', '悟', '乌', '雾', '勿', '坞', '戊', '兀', '晤', '鹜', '痦', '寤', '骛', '芴', '杌', '焐', '阢', '婺', '鋈'),
				'to' => 'wù'
			),
			array(
				'from' => array('西', '息', '希', '吸', '惜', '稀', '悉', '析', '夕', '牺', '腊', '昔', '熙', '兮', '溪', '嘻', '锡', '晰', '樨', '熄', '膝', '栖', '郗', '犀', '曦', '奚', '羲', '唏', '蹊', '淅', '皙', '汐', '嬉', '茜', '熹', '烯', '翕', '蟋', '歙', '浠', '僖', '穸', '蜥', '螅', '菥', '舾', '矽', '粞', '硒', '醯', '欷', '鼷'),
				'to' => 'xī'
			),
			array(
				'from' => array('席', '习', '袭', '媳', '檄', '隰', '觋'),
				'to' => 'xí'
			),
			array(
				'from' => array('喜', '洗', '禧', '徙', '玺', '屣', '葸', '蓰', '铣'),
				'to' => 'xǐ'
			),
			array(
				'from' => array('系', '细', '戏', '隙', '饩', '阋', '禊', '舄'),
				'to' => 'xì'
			),
			array(
				'from' => array('瞎', '虾', '呷'),
				'to' => 'xiā'
			),
			array(
				'from' => array('峡', '侠', '狭', '霞', '暇', '辖', '遐', '匣', '黠', '瑕', '狎', '硖', '瘕', '柙'),
				'to' => 'xiá'
			),
			array(
				'from' => array('下', '夏', '吓', '厦', '唬', '罅'),
				'to' => 'xià'
			),
			array(
				'from' => array('先', '鲜', '仙', '掀', '纤', '暹', '莶', '锨', '氙', '祆', '籼', '酰', '跹'),
				'to' => 'xiān'
			),
			array(
				'from' => array('闲', '贤', '嫌', '咸', '弦', '娴', '衔', '涎', '舷', '鹇', '痫'),
				'to' => 'xián'
			),
			array(
				'from' => array('显', '险', '鲜', '洗', '跣', '猃', '藓', '铣', '燹', '蚬', '筅', '冼'),
				'to' => 'xiǎn'
			),
			array(
				'from' => array('现', '见', '线', '限', '县', '献', '宪', '陷', '羡', '馅', '腺', '岘', '苋', '霰'),
				'to' => 'xiàn'
			),
			array(
				'from' => array('相', '香', '乡', '箱', '厢', '湘', '镶', '襄', '骧', '葙', '芗', '缃'),
				'to' => 'xiāng'
			),
			array(
				'from' => array('降', '详', '祥', '翔', '庠'),
				'to' => 'xiáng'
			),
			array(
				'from' => array('想', '响', '享', '飨', '饷', '鲞'),
				'to' => 'xiǎng'
			),
			array(
				'from' => array('相', '向', '象', '像', '项', '巷', '橡', '蟓'),
				'to' => 'xiàng'
			),
			array(
				'from' => array('消', '销', '潇', '肖', '萧', '宵', '削', '嚣', '逍', '硝', '霄', '哮', '枭', '骁', '箫', '枵', '哓', '蛸', '绡', '魈'),
				'to' => 'xiāo'
			),
			array(
				'from' => array('淆', '崤'),
				'to' => 'xiáo'
			),
			array(
				'from' => array('小', '晓', '筱'),
				'to' => 'xiǎo'
			),
			array(
				'from' => array('笑', '校', '效', '肖', '孝', '啸'),
				'to' => 'xiào'
			),
			array(
				'from' => array('些', '歇', '楔', '蝎'),
				'to' => 'xiē'
			),
			array(
				'from' => array('叶', '协', '鞋', '携', '斜', '胁', '谐', '邪', '挟', '偕', '撷', '勰', '颉', '缬'),
				'to' => 'xié'
			),
			array(
				'from' => array('写', '血'),
				'to' => 'xiě'
			),
			array(
				'from' => array('写', '解', '谢', '泄', '契', '械', '屑', '卸', '懈', '泻', '亵', '蟹', '邂', '榭', '瀣', '薤', '燮', '躞', '廨', '绁', '渫', '榍', '獬'),
				'to' => 'xiè'
			),
			array(
				'from' => array('心', '新', '欣', '辛', '薪', '馨', '鑫', '芯', '昕', '忻', '歆', '锌'),
				'to' => 'xīn'
			),
			array(
				'from' => array('寻', '镡'),
				'to' => 'xín'
			),
			array(
				'from' => array('信', '芯', '衅', '囟'),
				'to' => 'xìn'
			),
			array(
				'from' => array('兴', '星', '腥', '惺', '猩'),
				'to' => 'xīng'
			),
			array(
				'from' => array('行', '形', '型', '刑', '邢', '陉', '荥', '饧', '硎'),
				'to' => 'xíng'
			),
			array(
				'from' => array('省', '醒', '擤'),
				'to' => 'xǐng'
			),
			array(
				'from' => array('性', '兴', '姓', '幸', '杏', '悻', '荇'),
				'to' => 'xìng'
			),
			array(
				'from' => array('兄', '胸', '凶', '匈', '汹', '芎'),
				'to' => 'xiōng'
			),
			array(
				'from' => array('雄', '熊'),
				'to' => 'xióng'
			),
			array(
				'from' => array('修', '休', '羞', '咻', '馐', '庥', '鸺', '貅', '髹'),
				'to' => 'xiū'
			),
			array(
				'from' => array('宿', '朽'),
				'to' => 'xiǔ'
			),
			array(
				'from' => array('秀', '袖', '宿', '臭', '绣', '锈', '嗅', '岫', '溴'),
				'to' => 'xiù'
			),
			array(
				'from' => array('需', '须', '虚', '吁', '嘘', '墟', '戌', '胥', '砉', '圩', '盱', '顼'),
				'to' => 'xū'
			),
			array(
				'from' => array('徐'),
				'to' => 'xú'
			),
			array(
				'from' => array('许', '浒', '栩', '诩', '糈', '醑'),
				'to' => 'xǔ'
			),
			array(
				'from' => array('续', '序', '绪', '蓄', '叙', '畜', '恤', '絮', '旭', '婿', '酗', '煦', '洫', '溆', '勖'),
				'to' => 'xù'
			),
			array(
				'from' => array('蓿'),
				'to' => 'xu'
			),
			array(
				'from' => array('宣', '喧', '轩', '萱', '暄', '谖', '揎', '儇', '煊'),
				'to' => 'xuān'
			),
			array(
				'from' => array('旋', '悬', '玄', '漩', '璇', '痃'),
				'to' => 'xuán'
			),
			array(
				'from' => array('选', '癣'),
				'to' => 'xuǎn'
			),
			array(
				'from' => array('旋', '券', '炫', '渲', '绚', '眩', '铉', '泫', '碹', '楦', '镟'),
				'to' => 'xuàn'
			),
			array(
				'from' => array('削', '靴', '薛'),
				'to' => 'xuē'
			),
			array(
				'from' => array('学', '穴', '噱', '踅', '泶'),
				'to' => 'xué'
			),
			array(
				'from' => array('雪', '鳕'),
				'to' => 'xuě'
			),
			array(
				'from' => array('血', '谑'),
				'to' => 'xuè'
			),
			array(
				'from' => array('熏', '勋', '荤', '醺', '薰', '埙', '曛', '窨', '獯'),
				'to' => 'xūn'
			),
			array(
				'from' => array('寻', '询', '巡', '循', '旬', '驯', '荀', '峋', '洵', '恂', '郇', '浔', '鲟'),
				'to' => 'xún'
			),
			array(
				'from' => array('训', '迅', '讯', '逊', '熏', '殉', '巽', '徇', '汛', '蕈', '浚'),
				'to' => 'xùn'
			),
			array(
				'from' => array('压', '雅', '呀', '押', '鸦', '哑', '鸭', '丫', '垭', '桠'),
				'to' => 'yā'
			),
			array(
				'from' => array('牙', '涯', '崖', '芽', '衙', '睚', '伢', '岈', '琊', '蚜'),
				'to' => 'yá'
			),
			array(
				'from' => array('雅', '瞧', '匹', '痖', '疋'),
				'to' => 'yǎ'
			),
			array(
				'from' => array('亚', '压', '讶', '轧', '娅', '迓', '揠', '氩', '砑'),
				'to' => 'yà'
			),
			array(
				'from' => array('呀'),
				'to' => 'ya'
			),
			array(
				'from' => array('烟', '燕', '咽', '殷', '焉', '淹', '阉', '腌', '嫣', '胭', '湮', '阏', '鄢', '菸', '崦', '恹'),
				'to' => 'yān'
			),
			array(
				'from' => array('言', '严', '研', '延', '沿', '颜', '炎', '阎', '盐', '岩', '铅', '蜒', '檐', '妍', '筵', '芫', '闫', '阽'),
				'to' => 'yán'
			),
			array(
				'from' => array('眼', '演', '掩', '衍', '奄', '俨', '偃', '魇', '鼹', '兖', '郾', '琰', '罨', '厣', '剡', '鼽'),
				'to' => 'yǎn'
			),
			array(
				'from' => array('研', '验', '沿', '厌', '燕', '宴', '咽', '雁', '焰', '艳', '谚', '彦', '焱', '晏', '唁', '砚', '堰', '赝', '餍', '滟', '酽', '谳'),
				'to' => 'yàn'
			),
			array(
				'from' => array('央', '泱', '秧', '鸯', '殃', '鞅'),
				'to' => 'yāng'
			),
			array(
				'from' => array('洋', '阳', '杨', '扬', '羊', '疡', '佯', '烊', '徉', '炀', '蛘'),
				'to' => 'yáng'
			),
			array(
				'from' => array('养', '仰', '痒', '氧'),
				'to' => 'yǎng'
			),
			array(
				'from' => array('样', '漾', '恙', '烊', '怏', '鞅'),
				'to' => 'yàng'
			),
			array(
				'from' => array('要', '约', '邀', '腰', '夭', '妖', '吆', '幺'),
				'to' => 'yāo'
			),
			array(
				'from' => array('摇', '遥', '姚', '陶', '尧', '谣', '瑶', '窑', '肴', '侥', '铫', '珧', '轺', '爻', '徭', '繇', '鳐'),
				'to' => 'yáo'
			),
			array(
				'from' => array('咬', '杳', '窈', '舀', '崾'),
				'to' => 'yǎo'
			),
			array(
				'from' => array('要', '药', '耀', '钥', '鹞', '曜', '疟'),
				'to' => 'yào'
			),
			array(
				'from' => array('耶', '噎', '椰', '掖'),
				'to' => 'yē'
			),
			array(
				'from' => array('爷', '耶', '邪', '揶', '铘'),
				'to' => 'yé'
			),
			array(
				'from' => array('也', '野', '冶'),
				'to' => 'yě'
			),
			array(
				'from' => array('业', '夜', '叶', '页', '液', '咽', '哗', '曳', '拽', '烨', '掖', '腋', '谒', '邺', '靥', '晔'),
				'to' => 'yè'
			),
			array(
				'from' => array('一', '医', '衣', '依', '椅', '伊', '漪', '咿', '揖', '噫', '猗', '壹', '铱', '欹', '黟'),
				'to' => 'yī'
			),
			array(
				'from' => array('移', '疑', '遗', '宜', '仪', '蛇', '姨', '夷', '怡', '颐', '彝', '咦', '贻', '迤', '痍', '胰', '沂', '饴', '圯', '荑', '诒', '眙', '嶷'),
				'to' => 'yí'
			),
			array(
				'from' => array('以', '已', '衣', '尾', '椅', '矣', '乙', '蚁', '倚', '迤', '蛾', '旖', '苡', '钇', '舣', '酏'),
				'to' => 'yǐ'
			),
			array(
				'from' => array('意', '义', '议', '易', '衣', '艺', '译', '异', '益', '亦', '亿', '忆', '谊', '抑', '翼', '役', '艾', '溢', '毅', '裔', '逸', '轶', '弈', '翌', '疫', '绎', '佚', '奕', '熠', '诣', '弋', '驿', '懿', '呓', '屹', '薏', '噫', '镒', '缢', '邑', '臆', '刈', '羿', '仡', '峄', '怿', '悒', '肄', '佾', '殪', '挹', '埸', '劓', '镱', '瘗', '癔', '翊', '蜴', '嗌', '翳'),
				'to' => 'yì'
			),
			array(
				'from' => array('因', '音', '烟', '阴', '姻', '殷', '茵', '荫', '喑', '湮', '氤', '堙', '洇', '铟'),
				'to' => 'yīn'
			),
			array(
				'from' => array('银', '吟', '寅', '淫', '垠', '鄞', '霪', '狺', '夤', '圻', '龈'),
				'to' => 'yín'
			),
			array(
				'from' => array('引', '隐', '饮', '瘾', '殷', '尹', '蚓', '吲'),
				'to' => 'yǐn'
			),
			array(
				'from' => array('印', '饮', '荫', '胤', '茚', '窨'),
				'to' => 'yìn'
			),
			array(
				'from' => array('应', '英', '鹰', '婴', '樱', '膺', '莺', '罂', '鹦', '缨', '瑛', '璎', '撄', '嘤'),
				'to' => 'yīng'
			),
			array(
				'from' => array('营', '迎', '赢', '盈', '蝇', '莹', '荧', '萤', '萦', '瀛', '楹', '嬴', '茔', '滢', '潆', '荥', '蓥'),
				'to' => 'yíng'
			),
			array(
				'from' => array('影', '颖', '颍', '瘿', '郢'),
				'to' => 'yǐng'
			),
			array(
				'from' => array('应', '硬', '映', '媵'),
				'to' => 'yìng'
			),
			array(
				'from' => array('育', '哟', '唷'),
				'to' => 'yō'
			),
			array(
				'from' => array('哟'),
				'to' => 'yo'
			),
			array(
				'from' => array('拥', '庸', '佣', '雍', '臃', '邕', '镛', '墉', '慵', '痈', '壅', '鳙', '饔'),
				'to' => 'yōng'
			),
			array(
				'from' => array('喁'),
				'to' => 'yóng'
			),
			array(
				'from' => array('永', '勇', '涌', '踊', '泳', '咏', '俑', '恿', '甬', '蛹'),
				'to' => 'yǒng'
			),
			array(
				'from' => array('用', '佣'),
				'to' => 'yòng'
			),
			array(
				'from' => array('优', '幽', '忧', '悠', '攸', '呦'),
				'to' => 'yōu'
			),
			array(
				'from' => array('由', '游', '油', '邮', '尤', '犹', '柚', '鱿', '莸', '尢', '铀', '猷', '疣', '蚰', '蝣', '蝤', '繇', '莜'),
				'to' => 'yóu'
			),
			array(
				'from' => array('有', '友', '黝', '酉', '莠', '牖', '铕', '卣'),
				'to' => 'yǒu'
			),
			array(
				'from' => array('有', '又', '右', '幼', '诱', '佑', '柚', '囿', '鼬', '宥', '侑', '蚴', '釉'),
				'to' => 'yòu'
			),
			array(
				'from' => array('於', '吁', '迂', '淤', '纡', '瘀'),
				'to' => 'yū'
			),
			array(
				'from' => array('于', '与', '余', '予', '鱼', '愚', '舆', '娱', '愉', '馀', '逾', '渔', '渝', '俞', '萸', '瑜', '隅', '揄', '榆', '虞', '禺', '谀', '腴', '竽', '妤', '臾', '欤', '觎', '盂', '窬', '蝓', '嵛', '狳', '舁', '雩'),
				'to' => 'yú'
			),
			array(
				'from' => array('与', '语', '雨', '予', '宇', '羽', '禹', '圄', '屿', '龉', '伛', '圉', '庾', '瘐', '窳', '俣'),
				'to' => 'yǔ'
			),
			array(
				'from' => array('与', '语', '育', '遇', '狱', '雨', '欲', '预', '玉', '愈', '谷', '域', '誉', '吁', '蔚', '寓', '豫', '粥', '郁', '喻', '裕', '浴', '御', '驭', '尉', '谕', '毓', '妪', '峪', '芋', '昱', '煜', '熨', '燠', '菀', '蓣', '饫', '阈', '鬻', '聿', '钰', '鹆', '鹬', '蜮'),
				'to' => 'yù'
			),
			array(
				'from' => array('冤', '渊', '鸳', '眢', '鸢', '箢'),
				'to' => 'yuān'
			),
			array(
				'from' => array('员', '元', '原', '园', '源', '圆', '缘', '援', '袁', '猿', '垣', '辕', '沅', '媛', '芫', '橼', '圜', '塬', '爰', '螈', '鼋'),
				'to' => 'yuán'
			),
			array(
				'from' => array('远'),
				'to' => 'yuǎn'
			),
			array(
				'from' => array('院', '愿', '怨', '苑', '媛', '掾', '垸', '瑗'),
				'to' => 'yuàn'
			),
			array(
				'from' => array('约', '曰'),
				'to' => 'yuē'
			),
			array(
				'from' => array('说', '月', '乐', '越', '阅', '跃', '悦', '岳', '粤', '钥', '刖', '瀹', '栎', '樾', '龠', '钺'),
				'to' => 'yuè'
			),
			array(
				'from' => array('晕', '氲'),
				'to' => 'yūn'
			),
			array(
				'from' => array('员', '云', '匀', '筠', '芸', '耘', '纭', '昀', '郧'),
				'to' => 'yún'
			),
			array(
				'from' => array('允', '陨', '殒', '狁'),
				'to' => 'yǔn'
			),
			array(
				'from' => array('员', '运', '均', '韵', '晕', '孕', '蕴', '酝', '愠', '熨', '郓', '韫', '恽'),
				'to' => 'yùn'
			),
			array(
				'from' => array('扎', '咂', '匝', '拶'),
				'to' => 'zā'
			),
			array(
				'from' => array('杂', '咱', '砸'),
				'to' => 'zá'
			),
			array(
				'from' => array('咋'),
				'to' => 'zǎ'
			),
			array(
				'from' => array('灾', '哉', '栽', '甾'),
				'to' => 'zāi'
			),
			array(
				'from' => array('载', '仔', '宰', '崽'),
				'to' => 'zǎi'
			),
			array(
				'from' => array('在', '再', '载'),
				'to' => 'zài'
			),
			array(
				'from' => array('簪', '糌'),
				'to' => 'zān'
			),
			array(
				'from' => array('咱'),
				'to' => 'zán'
			),
			array(
				'from' => array('攒', '拶', '昝', '趱'),
				'to' => 'zǎn'
			),
			array(
				'from' => array('赞', '暂', '瓒', '錾'),
				'to' => 'zàn'
			),
			array(
				'from' => array('咱'),
				'to' => 'zan'
			),
			array(
				'from' => array('赃', '臧', '锗'),
				'to' => 'zāng'
			),
			array(
				'from' => array('驵'),
				'to' => 'zǎng'
			),
			array(
				'from' => array('藏', '脏', '葬', '奘'),
				'to' => 'zàng'
			),
			array(
				'from' => array('遭', '糟'),
				'to' => 'zāo'
			),
			array(
				'from' => array('凿'),
				'to' => 'záo'
			),
			array(
				'from' => array('早', '澡', '枣', '蚤', '藻', '缲'),
				'to' => 'zǎo'
			),
			array(
				'from' => array('造', '灶', '躁', '噪', '皂', '燥', '唣'),
				'to' => 'zào'
			),
			array(
				'from' => array('则', '责', '泽', '择', '咋', '啧', '迮', '帻', '赜', '笮', '箦', '舴'),
				'to' => 'zé'
			),
			array(
				'from' => array('侧', '仄', '昃'),
				'to' => 'zè'
			),
			array(
				'from' => array('贼'),
				'to' => 'zéi'
			),
			array(
				'from' => array('怎'),
				'to' => 'zěn'
			),
			array(
				'from' => array('谮'),
				'to' => 'zèn'
			),
			array(
				'from' => array('曾', '增', '憎', '缯', '罾'),
				'to' => 'zēng'
			),
			array(
				'from' => array('赠', '综', '缯', '甑', '锃'),
				'to' => 'zèng'
			),
			array(
				'from' => array('查', '扎', '咋', '渣', '喳', '揸', '楂', '哳', '吒', '齄'),
				'to' => 'zhā'
			),
			array(
				'from' => array('炸', '扎', '札', '喋', '轧', '闸', '铡'),
				'to' => 'zhá'
			),
			array(
				'from' => array('眨', '砟'),
				'to' => 'zhǎ'
			),
			array(
				'from' => array('炸', '咋', '诈', '乍', '蜡', '栅', '榨', '柞', '吒', '咤', '痄', '蚱'),
				'to' => 'zhà'
			),
			array(
				'from' => array('摘', '侧', '斋'),
				'to' => 'zhāi'
			),
			array(
				'from' => array('择', '宅', '翟'),
				'to' => 'zhái'
			),
			array(
				'from' => array('窄'),
				'to' => 'zhǎi'
			),
			array(
				'from' => array('债', '祭', '寨', '砦', '瘵'),
				'to' => 'zhài'
			),
			array(
				'from' => array('占', '沾', '粘', '瞻', '詹', '毡', '谵', '旃'),
				'to' => 'zhān'
			),
			array(
				'from' => array('展', '斩', '辗', '盏', '崭', '搌'),
				'to' => 'zhǎn'
			),
			array(
				'from' => array('战', '站', '占', '颤', '绽', '湛', '蘸', '栈'),
				'to' => 'zhàn'
			),
			array(
				'from' => array('张', '章', '彰', '璋', '蟑', '樟', '漳', '嫜', '鄣', '獐'),
				'to' => 'zhāng'
			),
			array(
				'from' => array('长', '掌', '涨', '仉'),
				'to' => 'zhǎng'
			),
			array(
				'from' => array('丈', '涨', '帐', '障', '账', '胀', '仗', '杖', '瘴', '嶂', '幛'),
				'to' => 'zhàng'
			),
			array(
				'from' => array('着', '招', '朝', '嘲', '昭', '钊', '啁'),
				'to' => 'zhāo'
			),
			array(
				'from' => array('着'),
				'to' => 'zháo'
			),
			array(
				'from' => array('找', '爪', '沼'),
				'to' => 'zhǎo'
			),
			array(
				'from' => array('照', '赵', '召', '罩', '兆', '肇', '诏', '棹', '笊'),
				'to' => 'zhào'
			),
			array(
				'from' => array('折', '遮', '蜇'),
				'to' => 'zhē'
			),
			array(
				'from' => array('折', '哲', '辙', '辄', '谪', '蛰', '摺', '磔', '蜇'),
				'to' => 'zhé'
			),
			array(
				'from' => array('者', '褶', '锗', '赭'),
				'to' => 'zhě'
			),
			array(
				'from' => array('这', '浙', '蔗', '鹧', '柘'),
				'to' => 'zhè'
			),
			array(
				'from' => array('着'),
				'to' => 'zhe'
			),
			array(
				'from' => array('这'),
				'to' => 'zhèi'
			),
			array(
				'from' => array('真', '针', '珍', '斟', '贞', '侦', '甄', '臻', '箴', '砧', '桢', '溱', '蓁', '椹', '榛', '胗', '祯', '浈'),
				'to' => 'zhēn'
			),
			array(
				'from' => array('诊', '枕', '疹', '缜', '畛', '轸', '稹'),
				'to' => 'zhěn'
			),
			array(
				'from' => array('阵', '镇', '震', '圳', '振', '赈', '朕', '鸩'),
				'to' => 'zhèn'
			),
			array(
				'from' => array('正', '争', '征', '丁', '挣', '症', '睁', '徵', '蒸', '怔', '筝', '铮', '峥', '狰', '钲', '鲭'),
				'to' => 'zhēng'
			),
			array(
				'from' => array('整', '拯'),
				'to' => 'zhěng'
			),
			array(
				'from' => array('政', '正', '证', '挣', '郑', '症', '怔', '铮', '诤', '帧'),
				'to' => 'zhèng'
			),
			array(
				'from' => array('之', '只', '知', '指', '支', '织', '氏', '枝', '汁', '掷', '芝', '吱', '肢', '脂', '蜘', '栀', '卮', '胝', '祗'),
				'to' => 'zhī'
			),
			array(
				'from' => array('直', '指', '职', '值', '执', '植', '殖', '侄', '踯', '摭', '絷', '跖', '埴'),
				'to' => 'zhí'
			),
			array(
				'from' => array('只', '指', '纸', '止', '址', '旨', '徵', '趾', '咫', '芷', '枳', '祉', '轵', '黹', '酯'),
				'to' => 'zhǐ'
			),
			array(
				'from' => array('知', '至', '制', '识', '治', '志', '致', '质', '智', '置', '秩', '滞', '帜', '稚', '挚', '掷', '峙', '窒', '炙', '痔', '栉', '桎', '帙', '轾', '贽', '痣', '豸', '陟', '忮', '彘', '膣', '雉', '鸷', '骘', '蛭', '踬', '郅', '觯'),
				'to' => 'zhì'
			),
			array(
				'from' => array('中', '终', '钟', '忠', '衷', '锺', '盅', '忪', '螽', '舯'),
				'to' => 'zhōng'
			),
			array(
				'from' => array('种', '肿', '踵', '冢'),
				'to' => 'zhǒng'
			),
			array(
				'from' => array('中', '种', '重', '众', '仲'),
				'to' => 'zhòng'
			),
			array(
				'from' => array('周', '州', '洲', '粥', '舟', '诌', '啁'),
				'to' => 'zhōu'
			),
			array(
				'from' => array('轴', '妯', '碡'),
				'to' => 'zhóu'
			),
			array(
				'from' => array('肘', '帚'),
				'to' => 'zhǒu'
			),
			array(
				'from' => array('皱', '骤', '轴', '宙', '咒', '昼', '胄', '纣', '绉', '荮', '籀', '繇', '酎'),
				'to' => 'zhòu'
			),
			array(
				'from' => array('诸', '朱', '珠', '猪', '株', '蛛', '洙', '诛', '铢', '茱', '邾', '潴', '槠', '橥', '侏'),
				'to' => 'zhū'
			),
			array(
				'from' => array('术', '逐', '筑', '竹', '烛', '躅', '竺', '舳', '瘃'),
				'to' => 'zhú'
			),
			array(
				'from' => array('主', '属', '煮', '嘱', '瞩', '拄', '褚', '渚', '麈'),
				'to' => 'zhǔ'
			),
			array(
				'from' => array('住', '注', '助', '著', '驻', '祝', '筑', '柱', '铸', '伫', '贮', '箸', '炷', '蛀', '杼', '翥', '苎', '疰'),
				'to' => 'zhù'
			),
			array(
				'from' => array('抓', '挝'),
				'to' => 'zhuā'
			),
			array(
				'from' => array('爪'),
				'to' => 'zhuǎ'
			),
			array(
				'from' => array('拽'),
				'to' => 'zhuāi'
			),
			array(
				'from' => array('转'),
				'to' => 'zhuǎi'
			),
			array(
				'from' => array('曳', '拽', '嘬'),
				'to' => 'zhuài'
			),
			array(
				'from' => array('专', '砖', '颛'),
				'to' => 'zhuān'
			),
			array(
				'from' => array('转'),
				'to' => 'zhuǎn'
			),
			array(
				'from' => array('传', '转', '赚', '撰', '沌', '篆', '啭', '馔'),
				'to' => 'zhuàn'
			),
			array(
				'from' => array('装', '庄', '妆', '桩'),
				'to' => 'zhuāng'
			),
			array(
				'from' => array('奘'),
				'to' => 'zhuǎng'
			),
			array(
				'from' => array('状', '壮', '撞', '幢', '僮', '戆'),
				'to' => 'zhuàng'
			),
			array(
				'from' => array('追', '锥', '隹', '椎', '骓'),
				'to' => 'zhuī'
			),
			array(
				'from' => array('坠', '缀', '赘', '惴', '缒'),
				'to' => 'zhuì'
			),
			array(
				'from' => array('屯', '谆', '肫', '窀'),
				'to' => 'zhūn'
			),
			array(
				'from' => array('准'),
				'to' => 'zhǔn'
			),
			array(
				'from' => array('桌', '捉', '卓', '拙', '涿', '焯', '倬'),
				'to' => 'zhuō'
			),
			array(
				'from' => array('着', '著', '琢', '缴', '灼', '酌', '浊', '濯', '茁', '啄', '斫', '镯', '诼', '禚', '擢', '浞'),
				'to' => 'zhuó'
			),
			array(
				'from' => array('资', '咨', '滋', '仔', '姿', '吱', '兹', '孜', '谘', '呲', '龇', '锱', '辎', '淄', '髭', '赀', '孳', '粢', '趑', '觜', '訾', '缁', '鲻', '嵫'),
				'to' => 'zī'
			),
			array(
				'from' => array('子', '紫', '仔', '梓', '姊', '籽', '滓', '秭', '笫', '耔', '茈', '訾'),
				'to' => 'zǐ'
			),
			array(
				'from' => array('自', '字', '渍', '恣', '眦'),
				'to' => 'zì'
			),
			array(
				'from' => array('宗', '踪', '综', '棕', '鬃', '枞', '腙'),
				'to' => 'zōng'
			),
			array(
				'from' => array('总', '偬'),
				'to' => 'zǒng'
			),
			array(
				'from' => array('纵', '粽'),
				'to' => 'zòng'
			),
			array(
				'from' => array('邹', '诹', '陬', '鄹', '驺', '鲰'),
				'to' => 'zōu'
			),
			array(
				'from' => array('走'),
				'to' => 'zǒu'
			),
			array(
				'from' => array('奏', '揍'),
				'to' => 'zòu'
			),
			array(
				'from' => array('租', '菹'),
				'to' => 'zū'
			),
			array(
				'from' => array('足', '族', '卒', '镞'),
				'to' => 'zú'
			),
			array(
				'from' => array('组', '祖', '阻', '诅', '俎'),
				'to' => 'zǔ'
			),
			array(
				'from' => array('钻', '躜'),
				'to' => 'zuān'
			),
			array(
				'from' => array('纂', '缵'),
				'to' => 'zuǎn'
			),
			array(
				'from' => array('赚', '钻', '攥'),
				'to' => 'zuàn'
			),
			array(
				'from' => array('堆'),
				'to' => 'zuī'
			),
			array(
				'from' => array('嘴', '咀', '觜'),
				'to' => 'zuǐ'
			),
			array(
				'from' => array('最', '罪', '醉', '蕞'),
				'to' => 'zuì'
			),
			array(
				'from' => array('尊', '遵', '樽', '鳟'),
				'to' => 'zūn'
			),
			array(
				'from' => array('撙'),
				'to' => 'zǔn'
			),
			array(
				'from' => array('作', '嘬'),
				'to' => 'zuō'
			),
			array(
				'from' => array('作', '昨', '琢', '笮'),
				'to' => 'zuó'
			),
			array(
				'from' => array('左', '佐', '撮'),
				'to' => 'zuǒ'
			),
			array(
				'from' => array('作', '做', '坐', '座', '凿', '柞', '怍', '胙', '阼', '唑', '祚', '酢'),
				'to' => 'zuò'
			)
		);

		foreach($pinyin as $replacement) {
			foreach($replacement['from'] as $char) {
				$string = str_replace($char, $replacement['to'], $string);
			}
		}

		return $string;
	}
	


}