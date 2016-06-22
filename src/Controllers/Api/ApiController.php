<?php namespace Sanatorium\Hoofmanager\Controllers\Api;

use Platform\Foundation\Controllers\Controller;
use Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface;
use Route;
use Sentinel;
use Input;
use Mail;
use Event;

class ApiController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
		'auth'
	];

	protected $except = [
		'hoofmanager/api/*'
	];

	/**
	 * The Hoofmanager repository.
	 *
	 * @var \Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface
	 */
	protected $apilogs;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	public static $calls = [

		'api/index' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.index',
			'description' => 'Test route'
		],

		'api/auth' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.auth',
			'description' => 'Autentifikuje uživatele zadaným jménem a heslem, případně vrátí další informace o uživateli',
			'request' => '
{
	"email" : "priklad@example.com",
	"password" : "heslo1234"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	id: "1",
	email: "john.doe@example.com",
	permissions: {
		admin: true
	},
	last_login: {
		date: "2014-02-17 03:44:31",
		timezone_type: 3,
		timezone: "UTC"
	},
	first_name: "John",
	last_name: "Doe",
	created_at: "2014-02-17 02:43:01",
	updated_at: "2014-02-17 02:43:37"
}'
				]
			]
		],

		'api/register' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.register',
			'description' => 'Registruje uzivatele do systemu',
			'request' => '
{
	"email" : "priklad@example.com",
	"password" : "heslo1234"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '{
success: true,
messages: [],
user: {
	id: "1",
	email: "john.doe@example.com",
	permissions: {
		admin: true
	},
	last_login: {
		date: "2014-02-17 03:44:31",
		timezone_type: 3,
		timezone: "UTC"
	},
	first_name: "John",
	last_name: "Doe",
	created_at: "2014-02-17 02:43:01",
	updated_at: "2014-02-17 02:43:37"
}
}'
				],
			'error' => [
					'status' => 200,
					'content' => '{
success: false,
messages: ["Duvod zamitnuti 1", "Duvod zamitnuti 2"],
user: null
}'
				]
			]
		],
		
		// houses
		'api/houses/create' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.houses.create',
			'description' => 'Vytvoří nový chov se zadaným číslem',
			'request' => '
{
	"cattle_number" : "25625235856xxx"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
    id: "1",
    cattle_number : "25625235856xxx",
    created_at: "2014-02-17 02:43:01",
    updated_at: "2014-02-17 02:43:37"
}'
				]
			]
		],

		'api/houses/grid' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.houses.all',
			'description' => 'Vypíše seznam založených chovů',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	total: 6,
	filtered: 6,
	throttle: 100,
	threshold: 100,
	page: 1,
	pages: 1,
	previous_page: null,
	next_page: null,
	per_page: 6,
	sort: "created_at",
	direction: "desc",
	default_column: "created_at",
	results: [
	{
		id: 6,
		cattle_number: "123445",
		created_at: "2015-09-10 11:16:48",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/houses/6",
		values: [ ],
		items: [ ]
	},
	{
		id: 5,
		cattle_number: "123445",
		created_at: "2015-09-10 11:16:37",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/houses/5",
		values: [ ],
		items: [ ]
	},
	{
		id: 4,
		cattle_number: "123445",
		created_at: "2015-09-10 11:16:13",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/houses/4",
		values: [ ],
		items: [ ]
	},
	{
		id: 3,
		cattle_number: "123445",
		created_at: "2015-09-10 11:15:16",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/houses/3",
		values: [ ],
		items: [ ]
	},
	{
		id: 2,
		cattle_number: "123445",
		created_at: "2015-09-10 11:14:10",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/houses/2",
		values: [ ],
		items: [ ]
	},
	{
		id: 1,
		cattle_number: "123445",
		created_at: "2015-09-10 11:13:15",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/houses/1",
		values: [ ],
		items: [ ]
	}
	]
}'
				]
			]
		],

		'api/houses/grid/simple' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.houses.all.simple',
			'description' => 'Vypíše seznam založených chovů zjednodušeně',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
[
	{
		id: 1,
		cattle_number: "123445"
	},
	{
		id: 2,
		cattle_number: "123445"
	},
	{
		id: 3,
		cattle_number: "123445"
	},
	{
		id: 4,
		cattle_number: "123445"
	},
	{
		id: 5,
		cattle_number: "123445"
	},
	{
		id: 6,
		cattle_number: "123445"
	}
]'
				]
			],
		],

		// diseases
		'api/diseases/create' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.diseases.create',
			'description' => 'Vytvoří nový nemoc zadaného jména',
			'request' => '
{
	"name" : "Rusterholzův vřed"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	id: 1,
	name: "Rusterholzův vřed",
	created_at: "2015-09-09 11:33:41",
	edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/diseases/1",
	values: [ ]
}'
				]
			]
		],

		'api/diseases/grid' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.diseases.all',
			'description' => 'Vypíše seznam založených nemocí',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	total: 1,
	filtered: 1,
	throttle: 100,
	threshold: 100,
	page: 1,
	pages: 1,
	previous_page: null,
	next_page: null,
	per_page: 1,
	sort: "created_at",
	direction: "desc",
	default_column: "created_at",
	results: [
	{
		id: 1,
		name: "Rusterholzův vřed",
		created_at: "2015-09-09 11:33:41",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/diseases/1",
		values: [ ]
	}
	]
}'
				]
			],
		],

		'api/diseases/grid/simple' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.diseases.all.simple',
			'description' => 'Vypíše seznam založených nemocí zjednodušeně',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
[
	{
		id: 1,
		name: "Rusterholzův vřed"
	}
]'
				]
			],
		],

		// items
		'api/items/create' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.items.create',
			'description' => 'Vytvoří kus dobytka',
			'request' => '
{
	"item_number" : "666111"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	id: 50,
	item_number: "666111",
	created_at: "2015-09-15 18:35:00",
	edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/50",
	values: [ ]
}'
				]
			]
		],

		'api/items/grid' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.items.all',
			'description' => 'Vypíše seznam založených kusů dobytka',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	total: 19,
	filtered: 19,
	throttle: 100,
	threshold: 100,
	page: 1,
	pages: 1,
	previous_page: null,
	next_page: null,
	per_page: 19,
	sort: "created_at",
	direction: "desc",
	default_column: "created_at",
	results: [
	{
		id: 50,
		item_number: "766443/566",
		created_at: "2015-09-15 18:35:00",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/50",
		values: [ ]
	},
	{
		id: 49,
		item_number: "111",
		created_at: "2015-09-15 13:17:51",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/49",
		values: [ ]
	},
	{
		id: 48,
		item_number: "1236",
		created_at: "2015-09-15 11:19:26",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/48",
		values: [ ]
	},
	{
		id: 47,
		item_number: "589",
		created_at: "2015-09-15 11:02:10",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/47",
		values: [ ]
	},
	{
		id: 46,
		item_number: "234567981",
		created_at: "2015-09-15 03:19:01",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/46",
		values: [ ]
	},
	{
		id: 45,
		item_number: "xxx",
		created_at: "2015-09-12 10:36:40",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/45",
		values: [ ]
	},
	{
		id: 13,
		item_number: "1",
		created_at: "2015-09-12 10:31:07",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/13",
		values: [ ]
	},
	{
		id: 12,
		item_number: null,
		created_at: "2015-09-11 17:40:19",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/12",
		values: [ ]
	},
	{
		id: 11,
		item_number: null,
		created_at: "2015-09-11 17:39:32",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/11",
		values: [ ]
	},
	{
		id: 10,
		item_number: null,
		created_at: "2015-09-11 17:38:19",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/10",
		values: [ ]
	},
	{
		id: 9,
		item_number: null,
		created_at: "2015-09-11 17:37:29",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/9",
		values: [ ]
	},
	{
		id: 8,
		item_number: null,
		created_at: "2015-09-11 17:33:37",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/8",
		values: [ ]
	},
	{
		id: 7,
		item_number: null,
		created_at: "2015-09-11 16:31:29",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/7",
		values: [ ]
	},
	{
		id: 6,
		item_number: null,
		created_at: "2015-09-11 16:20:17",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/6",
		values: [ ]
	},
	{
		id: 5,
		item_number: null,
		created_at: "2015-09-11 16:19:34",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/5",
		values: [ ]
	},
	{
		id: 4,
		item_number: null,
		created_at: "2015-09-11 16:19:31",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/4",
		values: [ ]
	},
	{
		id: 3,
		item_number: null,
		created_at: "2015-09-11 16:17:59",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/3",
		values: [ ]
	},
	{
		id: 2,
		item_number: null,
		created_at: "2015-09-11 16:17:29",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/2",
		values: [ ]
	},
	{
		id: 1,
		item_number: null,
		created_at: "2015-09-11 16:17:29",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/items/1",
		values: [ ]
	}
	]
}'
				]
			],
		],

		'api/items/grid/simple' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.items.all.simple',
			'description' => 'Vypíše seznam založených kusů dobytka zjednodušeně',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
[
{
	id: 1,
	item_number: null
},
{
	id: 2,
	item_number: null
},
{
	id: 3,
	item_number: null
},
{
	id: 4,
	item_number: null
},
{
	id: 5,
	item_number: null
},
{
	id: 6,
	item_number: null
},
{
	id: 7,
	item_number: null
},
{
	id: 8,
	item_number: null
},
{
	id: 9,
	item_number: null
},
{
	id: 10,
	item_number: null
},
{
	id: 11,
	item_number: null
},
{
	id: 12,
	item_number: null
},
{
	id: 13,
	item_number: "1"
},
{
	id: 45,
	item_number: "xxx"
},
{
	id: 46,
	item_number: "234567981"
},
{
	id: 47,
	item_number: "589"
},
{
	id: 48,
	item_number: "1236"
},
{
	id: 49,
	item_number: "111"
},
{
	id: 50,
	item_number: "766443/566"
}
]'
				]
			],
		],

		// Report
		'api/report' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.examinations.create',
			'description' => 'Vytvořit data o vyšetření',
			'request' => '
{
	user_id : "3", 		// volitelne (muze byt username+password)
	examinations: [
		{
			house_id: 1,
			item_id: 6,
			loc_score: 1,
			diseases: [
				{
					part_id: 2,
					subpart_id: 3,
					treatment: \'Bez ošetření\',
					disease_id: 5,
					check_date: \'2015-09-11 17:33:37\',
					type: \'fup\',
				}
			],
			healthy: [1,3,4]
		},
		{
			house_id: 1,
			item_id: 8,
			loc_score: "pohybliva",
			diseases: [
				{
					part_id: 4,
					subpart_id: 6,
					treatment: \'Bez ošetření\',
					disease_id: 1,
					check_date: \'2015-09-11 17:33:37\',
					type: \'fup\',
				},
				{
					part_id: 2,
					subpart_id: 12,
					treatment: \'Bez ošetření\',
					disease_id: 5,
					check_date: \'2015-09-11 17:33:37\',
					type: \'fup\',
				}
			],
			healthy: [1,3]
		}
	]
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{success: true}'
				]
			],
		],

		'api/vet/auth' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.vet.auth',
			'description' => 'Autentifikuje veteřináře zadaným jménem a heslem a vrátí všechna, pro něj validní, data',
			'request' => '
{
	"email" : "priklad@example.com",
	"password" : "heslo1234"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	id: 3,
	email: "matejlukas12@gmail.com",
	password: "$2y$10$tqOwasbQ9/N/Hb8KQIXHROwkC/a5eGbS.cvap5ngWgmjBBBQ18eGK",
	permissions: [ ],
	last_login: "2015-09-10 10:51:14",
	first_name: "Matěj",
	last_name: "Lukáš",
	created_at: "2015-09-07 09:50:14",
	updated_at: "2015-09-10 10:51:14",
	houses: {
		6: {
			id: 6,
			cattle_number: "123445",
			created_at: "2015-09-10 11:16:48",
			updated_at: "2015-09-10 11:16:48",
			pivot: {
				item_id: 45,
				house_id: 6
			},
			values: [ ],
			items: [
			{
				id: 45,
				item_number: "xxx",
				created_at: "2015-09-12 10:36:40",
				updated_at: "2015-09-12 10:36:40",
				pivot: {
					house_id: 6,
					item_id: 45
				},
				values: [ ]
			}
			]
		}
	},
	activated: true,
	values: [ ],
	examinations: [
	{
		id: 1,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:28:29",
		updated_at: "2015-09-11 16:28:29",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 2,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:29:17",
		updated_at: "2015-09-11 16:29:17",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 3,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:29:23",
		updated_at: "2015-09-11 16:29:23",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 4,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:29:40",
		updated_at: "2015-09-11 16:29:40",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 5,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:30:14",
		updated_at: "2015-09-11 16:30:14",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 6,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:30:50",
		updated_at: "2015-09-11 16:30:50",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 7,
		user_id: 3,
		item_id: 6,
		created_at: "2015-09-11 16:31:28",
		updated_at: "2015-09-11 16:31:28",
		values: [ ],
		item: {
			id: 6,
			item_number: null,
			created_at: "2015-09-11 16:20:17",
			updated_at: "2015-09-11 16:20:17",
			values: [ ],
			houses: [ ]
		},
		findings: [
		{
			id: 1,
			disease_id: 6,
			part_id: 2,
			subpart_id: 3,
			examination_id: 7,
			created_at: "2015-09-11 16:31:29",
			updated_at: "2015-09-11 16:31:29",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 6,
				name: null,
				created_at: "2015-09-11 16:31:29",
				updated_at: "2015-09-11 16:31:29",
				values: [ ]
			}
		}
		]
	},
	{
		id: 8,
		user_id: 3,
		item_id: 7,
		created_at: "2015-09-11 16:31:29",
		updated_at: "2015-09-11 16:31:29",
		values: [ ],
		item: {
			id: 7,
			item_number: null,
			created_at: "2015-09-11 16:31:29",
			updated_at: "2015-09-11 16:31:29",
			values: [ ],
			houses: [ ]
		},
		findings: [
		{
			id: 2,
			disease_id: 7,
			part_id: 4,
			subpart_id: 6,
			examination_id: 8,
			created_at: "2015-09-11 16:31:29",
			updated_at: "2015-09-11 16:31:29",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 7,
				name: null,
				created_at: "2015-09-11 16:31:29",
				updated_at: "2015-09-11 16:31:29",
				values: [ ]
			}
		},
		{
			id: 3,
			disease_id: 8,
			part_id: 2,
			subpart_id: 12,
			examination_id: 8,
			created_at: "2015-09-11 16:31:29",
			updated_at: "2015-09-11 16:31:29",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 8,
				name: null,
				created_at: "2015-09-11 16:31:29",
				updated_at: "2015-09-11 16:31:29",
				values: [ ]
			}
		}
		]
	},
	{
		id: 9,
		user_id: 3,
		item_id: 8,
		created_at: "2015-09-11 17:33:37",
		updated_at: "2015-09-11 17:33:37",
		values: [ ],
		item: {
			id: 8,
			item_number: null,
			created_at: "2015-09-11 17:33:37",
			updated_at: "2015-09-11 17:33:37",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 10,
		user_id: 3,
		item_id: 9,
		created_at: "2015-09-11 17:37:29",
		updated_at: "2015-09-11 17:37:29",
		values: [ ],
		item: {
			id: 9,
			item_number: null,
			created_at: "2015-09-11 17:37:29",
			updated_at: "2015-09-11 17:37:29",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 11,
		user_id: 3,
		item_id: 10,
		created_at: "2015-09-11 17:38:19",
		updated_at: "2015-09-11 17:38:19",
		values: [ ],
		item: {
			id: 10,
			item_number: null,
			created_at: "2015-09-11 17:38:19",
			updated_at: "2015-09-11 17:38:19",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 12,
		user_id: 3,
		item_id: 11,
		created_at: "2015-09-11 17:39:33",
		updated_at: "2015-09-11 17:39:33",
		values: [ ],
		item: {
			id: 11,
			item_number: null,
			created_at: "2015-09-11 17:39:32",
			updated_at: "2015-09-11 17:39:32",
			values: [ ],
			houses: [ ]
		},
		findings: [ ]
	},
	{
		id: 13,
		user_id: 3,
		item_id: 12,
		created_at: "2015-09-11 17:40:19",
		updated_at: "2015-09-11 17:40:19",
		values: [ ],
		item: {
			id: 12,
			item_number: null,
			created_at: "2015-09-11 17:40:19",
			updated_at: "2015-09-11 17:40:19",
			values: [ ],
			houses: [ ]
		},
		findings: [
		{
			id: 7,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 13,
			created_at: "2015-09-11 17:40:19",
			updated_at: "2015-09-11 17:40:19",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 8,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 13,
			created_at: "2015-09-11 17:40:19",
			updated_at: "2015-09-11 17:40:19",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	},
	{
		id: 14,
		user_id: 3,
		item_id: 13,
		created_at: "2015-09-12 10:31:07",
		updated_at: "2015-09-12 10:31:07",
		values: [ ],
		item: {
			id: 13,
			item_number: null,
			created_at: "2015-09-12 10:31:07",
			updated_at: "2015-09-12 10:31:07",
			values: [ ],
			houses: [ ]
		},
		findings: [
		{
			id: 9,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 14,
			created_at: "2015-09-12 10:31:07",
			updated_at: "2015-09-12 10:31:07",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 10,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 14,
			created_at: "2015-09-12 10:31:07",
			updated_at: "2015-09-12 10:31:07",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	},
	{
		id: 15,
		user_id: 3,
		item_id: 45,
		created_at: "2015-09-12 10:36:40",
		updated_at: "2015-09-12 10:36:40",
		values: [ ],
		item: {
			id: 45,
			item_number: "xxx",
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			houses: [
			{
				id: 6,
				cattle_number: "123445",
				created_at: "2015-09-10 11:16:48",
				updated_at: "2015-09-10 11:16:48",
				pivot: {
					item_id: 45,
					house_id: 6
				},
				values: [ ],
				items: [
				{
					id: 45,
					item_number: "xxx",
					created_at: "2015-09-12 10:36:40",
					updated_at: "2015-09-12 10:36:40",
					pivot: {
						house_id: 6,
						item_id: 45
					},
					values: [ ]
				}
				]
			}
			]
		},
		findings: [
		{
			id: 11,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 15,
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 12,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 15,
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	},
	{
		id: 16,
		user_id: 3,
		item_id: 45,
		created_at: "2015-09-12 10:37:42",
		updated_at: "2015-09-12 10:37:42",
		values: [ ],
		item: {
			id: 45,
			item_number: "xxx",
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			houses: [
			{
				id: 6,
				cattle_number: "123445",
				created_at: "2015-09-10 11:16:48",
				updated_at: "2015-09-10 11:16:48",
				pivot: {
					item_id: 45,
					house_id: 6
				},
				values: [ ],
				items: [
				{
					id: 45,
					item_number: "xxx",
					created_at: "2015-09-12 10:36:40",
					updated_at: "2015-09-12 10:36:40",
					pivot: {
						house_id: 6,
						item_id: 45
					},
					values: [ ]
				}
				]
			}
			]
		},
		findings: [
		{
			id: 13,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 16,
			created_at: "2015-09-12 10:37:42",
			updated_at: "2015-09-12 10:37:42",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 14,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 16,
			created_at: "2015-09-12 10:37:42",
			updated_at: "2015-09-12 10:37:42",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	},
	{
		id: 17,
		user_id: 3,
		item_id: 45,
		created_at: "2015-09-12 10:37:51",
		updated_at: "2015-09-12 10:37:51",
		values: [ ],
		item: {
			id: 45,
			item_number: "xxx",
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			houses: [
			{
				id: 6,
				cattle_number: "123445",
				created_at: "2015-09-10 11:16:48",
				updated_at: "2015-09-10 11:16:48",
				pivot: {
					item_id: 45,
					house_id: 6
				},
				values: [ ],
				items: [
				{
					id: 45,
					item_number: "xxx",
					created_at: "2015-09-12 10:36:40",
					updated_at: "2015-09-12 10:36:40",
					pivot: {
						house_id: 6,
						item_id: 45
					},
					values: [ ]
				}
				]
			}
			]
		},
		findings: [
		{
			id: 15,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 17,
			created_at: "2015-09-12 10:37:51",
			updated_at: "2015-09-12 10:37:51",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 16,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 17,
			created_at: "2015-09-12 10:37:51",
			updated_at: "2015-09-12 10:37:51",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	},
	{
		id: 18,
		user_id: 3,
		item_id: 45,
		created_at: "2015-09-12 10:38:30",
		updated_at: "2015-09-12 10:38:30",
		values: [ ],
		item: {
			id: 45,
			item_number: "xxx",
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			houses: [
			{
				id: 6,
				cattle_number: "123445",
				created_at: "2015-09-10 11:16:48",
				updated_at: "2015-09-10 11:16:48",
				pivot: {
					item_id: 45,
					house_id: 6
				},
				values: [ ],
				items: [
				{
					id: 45,
					item_number: "xxx",
					created_at: "2015-09-12 10:36:40",
					updated_at: "2015-09-12 10:36:40",
					pivot: {
						house_id: 6,
						item_id: 45
					},
					values: [ ]
				}
				]
			}
			]
		},
		findings: [
		{
			id: 17,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 18,
			created_at: "2015-09-12 10:38:30",
			updated_at: "2015-09-12 10:38:30",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 18,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 18,
			created_at: "2015-09-12 10:38:30",
			updated_at: "2015-09-12 10:38:30",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	},
	{
		id: 19,
		user_id: 3,
		item_id: 45,
		created_at: "2015-09-12 10:40:35",
		updated_at: "2015-09-12 10:40:35",
		values: [ ],
		item: {
			id: 45,
			item_number: "xxx",
			created_at: "2015-09-12 10:36:40",
			updated_at: "2015-09-12 10:36:40",
			values: [ ],
			houses: [
			{
				id: 6,
				cattle_number: "123445",
				created_at: "2015-09-10 11:16:48",
				updated_at: "2015-09-10 11:16:48",
				pivot: {
					item_id: 45,
					house_id: 6
				},
				values: [ ],
				items: [
				{
					id: 45,
					item_number: "xxx",
					created_at: "2015-09-12 10:36:40",
					updated_at: "2015-09-12 10:36:40",
					pivot: {
						house_id: 6,
						item_id: 45
					},
					values: [ ]
				}
				]
			}
			]
		},
		findings: [
		{
			id: 19,
			disease_id: 11,
			part_id: 2,
			subpart_id: 40,
			examination_id: 19,
			created_at: "2015-09-12 10:40:35",
			updated_at: "2015-09-12 10:40:35",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		},
		{
			id: 20,
			disease_id: 11,
			part_id: 2,
			subpart_id: 4,
			examination_id: 19,
			created_at: "2015-09-12 10:40:35",
			updated_at: "2015-09-12 10:40:35",
			values: [ ],
			part: null,
			subpart: null,
			disease: {
				id: 11,
				name: "Nemoc 6",
				created_at: "2015-09-11 17:39:33",
				updated_at: "2015-09-11 17:39:33",
				values: [ ]
			}
		}
		]
	}
	],
	activations: [
	{
		id: 5,
		user_id: 3,
		code: "GgLNtUaiCWZYKzg9Bif84xAUPs0sHd11",
		completed: true,
		completed_at: "2015-09-07 12:46:42",
		created_at: "2015-09-07 12:46:41",
		updated_at: "2015-09-07 12:46:42"
	}
	]
}'
				]
			]
		],

		// Dobytek
		'api/items/[id]' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.items.view',
			'description' => 'Zobrazit detail dobytka',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '{
id: 159,
item_number: "313519953",
created_at: "2015-09-22 17:54:27",
updated_at: "2015-09-22 17:54:27",
values: [ ],
houses: [
{
	id: 7,
	cattle_number: "123451",
	created_at: "2015-09-15 02:50:43",
	updated_at: "2015-09-15 02:50:43",
	company_name: "ZOD Zálší - Farma Jehnědí",
	label: "ZOD Zálší - Farma Jehnědí, , ",
	pivot: {
		item_id: 159,
		house_id: 7
	},
	values: [
	{
		id: 26,
		attribute_id: 6,
		entity_type: "Sanatorium\Hoofmanager\Models\House",
		entity_id: 7,
		value: "ZOD Zálší - Farma Jehnědí",
		created_at: "2015-09-15 02:51:14",
		updated_at: "2015-09-15 02:51:14",
		attribute: {
			id: 6,
			namespace: "sanatorium/hoofmanager.houses",
			slug: "company_name",
			name: "Název firmy",
			description: "Název chovu",
			type: "input",
			options: [ ],
			validation: null,
			enabled: true,
			created_at: "2015-09-12 13:56:48",
			updated_at: "2015-09-12 13:56:48"
		}
	}
	],
	items: [
	{
		id: 56,
		item_number: "281558953",
		created_at: "2015-09-17 17:34:15",
		updated_at: "2015-09-17 17:34:15",
		pivot: {
			house_id: 7,
			item_id: 56
		},
		values: [ ]
	},
	{
		id: 55,
		item_number: "281594953",
		created_at: "2015-09-17 17:31:44",
		updated_at: "2015-09-17 17:31:44",
		pivot: {
			house_id: 7,
			item_id: 55
		},
		values: [ ]
	},
	{
		id: 62,
		item_number: "293511953",
		created_at: "2015-09-18 04:46:28",
		updated_at: "2015-09-18 04:46:28",
		pivot: {
			house_id: 7,
			item_id: 62
		},
		values: [ ]
	},
	{
		id: 64,
		item_number: "220365953",
		created_at: "2015-09-18 04:46:28",
		updated_at: "2015-09-18 04:46:28",
		pivot: {
			house_id: 7,
			item_id: 64
		},
		values: [ ]
	},
	{
		id: 108,
		item_number: "313521953",
		created_at: "2015-09-19 07:11:27",
		updated_at: "2015-09-19 07:11:27",
		pivot: {
			house_id: 7,
			item_id: 108
		},
		values: [ ]
	},
	{
		id: 109,
		item_number: "220301953",
		created_at: "2015-09-19 07:11:28",
		updated_at: "2015-09-19 07:11:28",
		pivot: {
			house_id: 7,
			item_id: 109
		},
		values: [ ]
	},
	{
		id: 110,
		item_number: "247563953",
		created_at: "2015-09-19 10:00:39",
		updated_at: "2015-09-19 10:00:39",
		pivot: {
			house_id: 7,
			item_id: 110
		},
		values: [ ]
	},
	{
		id: 111,
		item_number: "313500953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 111
		},
		values: [ ]
	},
	{
		id: 112,
		item_number: "313989953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 112
		},
		values: [ ]
	},
	{
		id: 113,
		item_number: "266443953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 113
		},
		values: [ ]
	},
	{
		id: 114,
		item_number: "293503953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 114
		},
		values: [ ]
	},
	{
		id: 115,
		item_number: "163474953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 115
		},
		values: [ ]
	},
	{
		id: 116,
		item_number: "313501953",
		created_at: "2015-09-19 10:00:41",
		updated_at: "2015-09-19 10:00:41",
		pivot: {
			house_id: 7,
			item_id: 116
		},
		values: [ ]
	},
	{
		id: 117,
		item_number: "247573953",
		created_at: "2015-09-19 10:00:42",
		updated_at: "2015-09-19 10:00:42",
		pivot: {
			house_id: 7,
			item_id: 117
		},
		values: [ ]
	},
	{
		id: 118,
		item_number: "293519953",
		created_at: "2015-09-19 10:00:42",
		updated_at: "2015-09-19 10:00:42",
		pivot: {
			house_id: 7,
			item_id: 118
		},
		values: [ ]
	},
	{
		id: 119,
		item_number: "313993953",
		created_at: "2015-09-19 10:00:42",
		updated_at: "2015-09-19 10:00:42",
		pivot: {
			house_id: 7,
			item_id: 119
		},
		values: [ ]
	},
	{
		id: 120,
		item_number: "313523953",
		created_at: "2015-09-19 10:00:43",
		updated_at: "2015-09-19 10:00:43",
		pivot: {
			house_id: 7,
			item_id: 120
		},
		values: [ ]
	},
	{
		id: 121,
		item_number: "293479953",
		created_at: "2015-09-19 10:00:43",
		updated_at: "2015-09-19 10:00:43",
		pivot: {
			house_id: 7,
			item_id: 121
		},
		values: [ ]
	},
	{
		id: 122,
		item_number: "313503953",
		created_at: "2015-09-19 10:00:44",
		updated_at: "2015-09-19 10:00:44",
		pivot: {
			house_id: 7,
			item_id: 122
		},
		values: [ ]
	},
	{
		id: 123,
		item_number: "313493953",
		created_at: "2015-09-19 10:00:44",
		updated_at: "2015-09-19 10:00:44",
		pivot: {
			house_id: 7,
			item_id: 123
		},
		values: [ ]
	},
	{
		id: 124,
		item_number: "313508953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 124
		},
		values: [ ]
	},
	{
		id: 125,
		item_number: "247582953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 125
		},
		values: [ ]
	},
	{
		id: 126,
		item_number: "313507953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 126
		},
		values: [ ]
	},
	{
		id: 127,
		item_number: "313457953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 127
		},
		values: [ ]
	},
	{
		id: 128,
		item_number: "3131478953",
		created_at: "2015-09-19 11:53:08",
		updated_at: "2015-09-19 11:53:08",
		pivot: {
			house_id: 7,
			item_id: 128
		},
		values: [ ]
	},
	{
		id: 129,
		item_number: "220398953",
		created_at: "2015-09-19 11:53:09",
		updated_at: "2015-09-19 11:53:09",
		pivot: {
			house_id: 7,
			item_id: 129
		},
		values: [ ]
	},
	{
		id: 130,
		item_number: "313478953",
		created_at: "2015-09-19 11:53:10",
		updated_at: "2015-09-19 11:53:10",
		pivot: {
			house_id: 7,
			item_id: 130
		},
		values: [ ]
	},
	{
		id: 131,
		item_number: "313512953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 131
		},
		values: [ ]
	},
	{
		id: 134,
		item_number: "220320953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 134
		},
		values: [ ]
	},
	{
		id: 132,
		item_number: "293494953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 132
		},
		values: [ ]
	},
	{
		id: 133,
		item_number: "220399953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 133
		},
		values: [ ]
	},
	{
		id: 135,
		item_number: "293543953",
		created_at: "2015-09-19 16:24:01",
		updated_at: "2015-09-19 16:24:01",
		pivot: {
			house_id: 7,
			item_id: 135
		},
		values: [ ]
	},
	{
		id: 136,
		item_number: "220257953",
		created_at: "2015-09-19 16:24:02",
		updated_at: "2015-09-19 16:24:02",
		pivot: {
			house_id: 7,
			item_id: 136
		},
		values: [ ]
	},
	{
		id: 137,
		item_number: "313488953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 137
		},
		values: [ ]
	},
	{
		id: 138,
		item_number: "293528953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 138
		},
		values: [ ]
	},
	{
		id: 139,
		item_number: "266434953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 139
		},
		values: [ ]
	},
	{
		id: 140,
		item_number: "313974953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 140
		},
		values: [ ]
	},
	{
		id: 141,
		item_number: "220388953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 141
		},
		values: [ ]
	},
	{
		id: 142,
		item_number: "220340953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 142
		},
		values: [ ]
	},
	{
		id: 143,
		item_number: "220383953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 143
		},
		values: [ ]
	},
	{
		id: 144,
		item_number: "313474953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 144
		},
		values: [ ]
	},
	{
		id: 145,
		item_number: "313497953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 145
		},
		values: [ ]
	},
	{
		id: 146,
		item_number: "313506953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 146
		},
		values: [ ]
	},
	{
		id: 147,
		item_number: "293499953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 147
		},
		values: [ ]
	},
	{
		id: 148,
		item_number: "266398953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 148
		},
		values: [ ]
	},
	{
		id: 149,
		item_number: "281645953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 149
		},
		values: [ ]
	},
	{
		id: 151,
		item_number: "220343953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 151
		},
		values: [ ]
	},
	{
		id: 150,
		item_number: "181722953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 150
		},
		values: [ ]
	},
	{
		id: 152,
		item_number: "313459953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 152
		},
		values: [ ]
	},
	{
		id: 153,
		item_number: "313498953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 153
		},
		values: [ ]
	},
	{
		id: 154,
		item_number: "313530953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 154
		},
		values: [ ]
	},
	{
		id: 155,
		item_number: "303746953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 155
		},
		values: [ ]
	},
	{
		id: 156,
		item_number: "293533953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 156
		},
		values: [ ]
	},
	{
		id: 158,
		item_number: "220334954",
		created_at: "2015-09-19 16:24:07",
		updated_at: "2015-09-19 16:24:07",
		pivot: {
			house_id: 7,
			item_id: 158
		},
		values: [ ]
	},
	{
		id: 157,
		item_number: "313499953",
		created_at: "2015-09-19 16:24:07",
		updated_at: "2015-09-19 16:24:07",
		pivot: {
			house_id: 7,
			item_id: 157
		},
		values: [ ]
	},
	{
		id: 159,
		item_number: "313519953",
		created_at: "2015-09-22 17:54:27",
		updated_at: "2015-09-22 17:54:27",
		pivot: {
			house_id: 7,
			item_id: 159
		},
		values: [ ]
	}
	]
}
],
examinations: [
{
	id: 212,
	user_id: 6,
	item_id: 159,
	created_at: "2015-09-22 17:54:27",
	updated_at: "2015-09-22 17:54:27",
	values: [ ],
	item: {
		id: 159,
		item_number: "313519953",
		created_at: "2015-09-22 17:54:27",
		updated_at: "2015-09-22 17:54:27",
		values: [ ]
	},
	findings: [ ]
}
]
}'
				]
			],
		],

				// Dobytek
		'api/items/bynumber/[item_number]' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.items.view.bynumber',
			'description' => 'Zobrazit detail dobytka podle čísla dobytka',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '{
id: 159,
item_number: "313519953",
created_at: "2015-09-22 17:54:27",
updated_at: "2015-09-22 17:54:27",
values: [ ],
houses: [
{
	id: 7,
	cattle_number: "123451",
	created_at: "2015-09-15 02:50:43",
	updated_at: "2015-09-15 02:50:43",
	company_name: "ZOD Zálší - Farma Jehnědí",
	label: "ZOD Zálší - Farma Jehnědí, , ",
	pivot: {
		item_id: 159,
		house_id: 7
	},
	values: [
	{
		id: 26,
		attribute_id: 6,
		entity_type: "Sanatorium\Hoofmanager\Models\House",
		entity_id: 7,
		value: "ZOD Zálší - Farma Jehnědí",
		created_at: "2015-09-15 02:51:14",
		updated_at: "2015-09-15 02:51:14",
		attribute: {
			id: 6,
			namespace: "sanatorium/hoofmanager.houses",
			slug: "company_name",
			name: "Název firmy",
			description: "Název chovu",
			type: "input",
			options: [ ],
			validation: null,
			enabled: true,
			created_at: "2015-09-12 13:56:48",
			updated_at: "2015-09-12 13:56:48"
		}
	}
	],
	items: [
	{
		id: 56,
		item_number: "281558953",
		created_at: "2015-09-17 17:34:15",
		updated_at: "2015-09-17 17:34:15",
		pivot: {
			house_id: 7,
			item_id: 56
		},
		values: [ ]
	},
	{
		id: 55,
		item_number: "281594953",
		created_at: "2015-09-17 17:31:44",
		updated_at: "2015-09-17 17:31:44",
		pivot: {
			house_id: 7,
			item_id: 55
		},
		values: [ ]
	},
	{
		id: 62,
		item_number: "293511953",
		created_at: "2015-09-18 04:46:28",
		updated_at: "2015-09-18 04:46:28",
		pivot: {
			house_id: 7,
			item_id: 62
		},
		values: [ ]
	},
	{
		id: 64,
		item_number: "220365953",
		created_at: "2015-09-18 04:46:28",
		updated_at: "2015-09-18 04:46:28",
		pivot: {
			house_id: 7,
			item_id: 64
		},
		values: [ ]
	},
	{
		id: 108,
		item_number: "313521953",
		created_at: "2015-09-19 07:11:27",
		updated_at: "2015-09-19 07:11:27",
		pivot: {
			house_id: 7,
			item_id: 108
		},
		values: [ ]
	},
	{
		id: 109,
		item_number: "220301953",
		created_at: "2015-09-19 07:11:28",
		updated_at: "2015-09-19 07:11:28",
		pivot: {
			house_id: 7,
			item_id: 109
		},
		values: [ ]
	},
	{
		id: 110,
		item_number: "247563953",
		created_at: "2015-09-19 10:00:39",
		updated_at: "2015-09-19 10:00:39",
		pivot: {
			house_id: 7,
			item_id: 110
		},
		values: [ ]
	},
	{
		id: 111,
		item_number: "313500953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 111
		},
		values: [ ]
	},
	{
		id: 112,
		item_number: "313989953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 112
		},
		values: [ ]
	},
	{
		id: 113,
		item_number: "266443953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 113
		},
		values: [ ]
	},
	{
		id: 114,
		item_number: "293503953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 114
		},
		values: [ ]
	},
	{
		id: 115,
		item_number: "163474953",
		created_at: "2015-09-19 10:00:40",
		updated_at: "2015-09-19 10:00:40",
		pivot: {
			house_id: 7,
			item_id: 115
		},
		values: [ ]
	},
	{
		id: 116,
		item_number: "313501953",
		created_at: "2015-09-19 10:00:41",
		updated_at: "2015-09-19 10:00:41",
		pivot: {
			house_id: 7,
			item_id: 116
		},
		values: [ ]
	},
	{
		id: 117,
		item_number: "247573953",
		created_at: "2015-09-19 10:00:42",
		updated_at: "2015-09-19 10:00:42",
		pivot: {
			house_id: 7,
			item_id: 117
		},
		values: [ ]
	},
	{
		id: 118,
		item_number: "293519953",
		created_at: "2015-09-19 10:00:42",
		updated_at: "2015-09-19 10:00:42",
		pivot: {
			house_id: 7,
			item_id: 118
		},
		values: [ ]
	},
	{
		id: 119,
		item_number: "313993953",
		created_at: "2015-09-19 10:00:42",
		updated_at: "2015-09-19 10:00:42",
		pivot: {
			house_id: 7,
			item_id: 119
		},
		values: [ ]
	},
	{
		id: 120,
		item_number: "313523953",
		created_at: "2015-09-19 10:00:43",
		updated_at: "2015-09-19 10:00:43",
		pivot: {
			house_id: 7,
			item_id: 120
		},
		values: [ ]
	},
	{
		id: 121,
		item_number: "293479953",
		created_at: "2015-09-19 10:00:43",
		updated_at: "2015-09-19 10:00:43",
		pivot: {
			house_id: 7,
			item_id: 121
		},
		values: [ ]
	},
	{
		id: 122,
		item_number: "313503953",
		created_at: "2015-09-19 10:00:44",
		updated_at: "2015-09-19 10:00:44",
		pivot: {
			house_id: 7,
			item_id: 122
		},
		values: [ ]
	},
	{
		id: 123,
		item_number: "313493953",
		created_at: "2015-09-19 10:00:44",
		updated_at: "2015-09-19 10:00:44",
		pivot: {
			house_id: 7,
			item_id: 123
		},
		values: [ ]
	},
	{
		id: 124,
		item_number: "313508953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 124
		},
		values: [ ]
	},
	{
		id: 125,
		item_number: "247582953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 125
		},
		values: [ ]
	},
	{
		id: 126,
		item_number: "313507953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 126
		},
		values: [ ]
	},
	{
		id: 127,
		item_number: "313457953",
		created_at: "2015-09-19 10:00:45",
		updated_at: "2015-09-19 10:00:45",
		pivot: {
			house_id: 7,
			item_id: 127
		},
		values: [ ]
	},
	{
		id: 128,
		item_number: "3131478953",
		created_at: "2015-09-19 11:53:08",
		updated_at: "2015-09-19 11:53:08",
		pivot: {
			house_id: 7,
			item_id: 128
		},
		values: [ ]
	},
	{
		id: 129,
		item_number: "220398953",
		created_at: "2015-09-19 11:53:09",
		updated_at: "2015-09-19 11:53:09",
		pivot: {
			house_id: 7,
			item_id: 129
		},
		values: [ ]
	},
	{
		id: 130,
		item_number: "313478953",
		created_at: "2015-09-19 11:53:10",
		updated_at: "2015-09-19 11:53:10",
		pivot: {
			house_id: 7,
			item_id: 130
		},
		values: [ ]
	},
	{
		id: 131,
		item_number: "313512953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 131
		},
		values: [ ]
	},
	{
		id: 134,
		item_number: "220320953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 134
		},
		values: [ ]
	},
	{
		id: 132,
		item_number: "293494953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 132
		},
		values: [ ]
	},
	{
		id: 133,
		item_number: "220399953",
		created_at: "2015-09-19 16:23:59",
		updated_at: "2015-09-19 16:23:59",
		pivot: {
			house_id: 7,
			item_id: 133
		},
		values: [ ]
	},
	{
		id: 135,
		item_number: "293543953",
		created_at: "2015-09-19 16:24:01",
		updated_at: "2015-09-19 16:24:01",
		pivot: {
			house_id: 7,
			item_id: 135
		},
		values: [ ]
	},
	{
		id: 136,
		item_number: "220257953",
		created_at: "2015-09-19 16:24:02",
		updated_at: "2015-09-19 16:24:02",
		pivot: {
			house_id: 7,
			item_id: 136
		},
		values: [ ]
	},
	{
		id: 137,
		item_number: "313488953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 137
		},
		values: [ ]
	},
	{
		id: 138,
		item_number: "293528953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 138
		},
		values: [ ]
	},
	{
		id: 139,
		item_number: "266434953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 139
		},
		values: [ ]
	},
	{
		id: 140,
		item_number: "313974953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 140
		},
		values: [ ]
	},
	{
		id: 141,
		item_number: "220388953",
		created_at: "2015-09-19 16:24:03",
		updated_at: "2015-09-19 16:24:03",
		pivot: {
			house_id: 7,
			item_id: 141
		},
		values: [ ]
	},
	{
		id: 142,
		item_number: "220340953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 142
		},
		values: [ ]
	},
	{
		id: 143,
		item_number: "220383953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 143
		},
		values: [ ]
	},
	{
		id: 144,
		item_number: "313474953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 144
		},
		values: [ ]
	},
	{
		id: 145,
		item_number: "313497953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 145
		},
		values: [ ]
	},
	{
		id: 146,
		item_number: "313506953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 146
		},
		values: [ ]
	},
	{
		id: 147,
		item_number: "293499953",
		created_at: "2015-09-19 16:24:04",
		updated_at: "2015-09-19 16:24:04",
		pivot: {
			house_id: 7,
			item_id: 147
		},
		values: [ ]
	},
	{
		id: 148,
		item_number: "266398953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 148
		},
		values: [ ]
	},
	{
		id: 149,
		item_number: "281645953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 149
		},
		values: [ ]
	},
	{
		id: 151,
		item_number: "220343953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 151
		},
		values: [ ]
	},
	{
		id: 150,
		item_number: "181722953",
		created_at: "2015-09-19 16:24:05",
		updated_at: "2015-09-19 16:24:05",
		pivot: {
			house_id: 7,
			item_id: 150
		},
		values: [ ]
	},
	{
		id: 152,
		item_number: "313459953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 152
		},
		values: [ ]
	},
	{
		id: 153,
		item_number: "313498953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 153
		},
		values: [ ]
	},
	{
		id: 154,
		item_number: "313530953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 154
		},
		values: [ ]
	},
	{
		id: 155,
		item_number: "303746953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 155
		},
		values: [ ]
	},
	{
		id: 156,
		item_number: "293533953",
		created_at: "2015-09-19 16:24:06",
		updated_at: "2015-09-19 16:24:06",
		pivot: {
			house_id: 7,
			item_id: 156
		},
		values: [ ]
	},
	{
		id: 158,
		item_number: "220334954",
		created_at: "2015-09-19 16:24:07",
		updated_at: "2015-09-19 16:24:07",
		pivot: {
			house_id: 7,
			item_id: 158
		},
		values: [ ]
	},
	{
		id: 157,
		item_number: "313499953",
		created_at: "2015-09-19 16:24:07",
		updated_at: "2015-09-19 16:24:07",
		pivot: {
			house_id: 7,
			item_id: 157
		},
		values: [ ]
	},
	{
		id: 159,
		item_number: "313519953",
		created_at: "2015-09-22 17:54:27",
		updated_at: "2015-09-22 17:54:27",
		pivot: {
			house_id: 7,
			item_id: 159
		},
		values: [ ]
	}
	]
}
],
examinations: [
{
	id: 212,
	user_id: 6,
	item_id: 159,
	created_at: "2015-09-22 17:54:27",
	updated_at: "2015-09-22 17:54:27",
	values: [ ],
	item: {
		id: 159,
		item_number: "313519953",
		created_at: "2015-09-22 17:54:27",
		updated_at: "2015-09-22 17:54:27",
		values: [ ]
	},
	findings: [ ]
}
]
}'
				]
			],
		],


		'api/treatments/grid' => [
			'method' => 'GET',
			'route' => 'sanatorium.hoofmanager.api.treatments.all',
			'description' => 'Vypíše seznam způsobů léčení',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	total: 10,
	filtered: 10,
	throttle: 100,
	threshold: 100,
	page: 1,
	pages: 1,
	previous_page: null,
	next_page: null,
	per_page: 10,
	sort: "created_at",
	direction: "desc",
	default_column: "created_at",
	results: [
	{
		id: 10,
		name: "metrycyklin",
		created_at: "2015-10-02 10:31:01",
		updated_at: "2015-10-02 10:31:01",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/10",
		values: [ ]
	},
	{
		id: 9,
		name: "Antrolan - N",
		created_at: "2015-10-02 10:30:53",
		updated_at: "2015-10-02 10:30:53",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/9",
		values: [ ]
	},
	{
		id: 8,
		name: "celkové ATB",
		created_at: "2015-10-02 10:30:44",
		updated_at: "2015-10-02 10:30:44",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/8",
		values: [ ]
	},
	{
		id: 7,
		name: "bez obvazu a chemickou podkovou",
		created_at: "2015-10-02 10:30:34",
		updated_at: "2015-10-02 10:30:34",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/7",
		values: [ ]
	},
	{
		id: 6,
		name: "s obvazem a chemickou podkovou",
		created_at: "2015-10-02 10:30:25",
		updated_at: "2015-10-02 10:30:25",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/6",
		values: [ ]
	},
	{
		id: 5,
		name: "s obvazem - s aureozásypem",
		created_at: "2015-10-02 10:30:16",
		updated_at: "2015-10-02 10:30:16",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/5",
		values: [ ]
	},
	{
		id: 4,
		name: "FaSy Hoofsolution",
		created_at: "2015-10-02 10:29:44",
		updated_at: "2015-10-02 10:29:44",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/4",
		values: [ ]
	},
	{
		id: 3,
		name: "FaSy Hoofsolution gel",
		created_at: "2015-10-02 10:29:28",
		updated_at: "2015-10-02 10:29:28",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/3",
		values: [ ]
	},
	{
		id: 2,
		name: "FaSy Hoofsolution gel + obvaz",
		created_at: "2015-10-02 10:29:16",
		updated_at: "2015-10-02 10:29:16",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/2",
		values: [ ]
	},
	{
		id: 1,
		name: "Bez ošetření",
		created_at: "2015-10-02 10:28:16",
		updated_at: "2015-10-02 10:28:16",
		edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/1",
		values: [ ]
	}
	]
}'
				]
			],
		],

		// treatments
		'api/treatments/create' => [
			'method' => 'POST',
			'route' => 'sanatorium.hoofmanager.api.treatments.create',
			'description' => 'Vytvoří metodu ošetření',
			'request' => '
{
	"name" : "Název ošetření"
}',
			'response' => [
				'success' => [
					'status' => 200,
					'content' => '
{
	id: 50,
	name: "Název ošetření",
	created_at: "2015-09-15 18:35:00",
	edit_uri: "http://hoofmanager.rozklad.me/admin/hoofmanager/treatments/50",
	values: [ ]
}'
				]
			]
		],
	];


	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Hoofmanager\Repositories\Apilog\ApilogRepositoryInterface  $apilogs
	 * @return void
	 */
	public function __construct(ApilogRepositoryInterface $apilogs)
	{
		parent::__construct();

		$this->apilogs = $apilogs;
	}

	public function api()
	{
		$this->result = ['test' => 'test'];
		$this->status = 200;
		return $this->result;
	}

	public function auth()
	{
		$credentials = [
			'email'    => Input::get('email'),
			'password' => Input::get('password'),
		];

		if ($user = Sentinel::authenticate($credentials))
		{
			$this->status = 200;
			$this->result = $user;
		}
		else
		{
			$this->status = 403;
    		$this->result = ['success' => false];
		}
		return $this->result;
	}

	public function register()
	{
		$input = request()->all();
		$input['password_confirmation'] = $input['password'];

		// Store the user
        list($messages, $user) = app('platform.users')->auth()->register($input);

        // Do we have any errors?
        if ($messages->isEmpty()) {
        	// Activate user
        	$user->save();
        	
        	Event::fire('hoofmanager.registration.success', ['user' => $user]);

            return [
	        	'success' => true,
	        	'messages' => [],
	        	'user' => $user
	        ];
        }

        Event::fire('hoofmanager.registration.failed', ['input' => $input]);

        return [
        	'success' => false,
        	'messages' => $messages,
        	'user' => null
        ];
	}

	public function __destruct()
	{
		$source = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
		$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;

		$object = [
			'source' 	=> $source,
			'method'	=> $method,

			'call' 		=> Route::currentRouteName(),

			'data' 		=> json_encode( request()->all() )
			];

		if ( isset($this->result) ) {
			$object['result'] = json_encode( $this->result );
		}
		if ( isset($this->status) ) {
			$object['status'] = json_encode( $this->status );
		}

		if ( isset($this->apilogs) ) {
			$this->apilogs->create($object);
		}

		/*
		Mail::send('email.api', ['msg' => var_export($object['data'], true)], function($message){

			$message->to('jan.rozklad@gmail.com')->cc('vzink@seznam.cz')->cc('neticek@gmail.com') 
		});*/
	}


}
