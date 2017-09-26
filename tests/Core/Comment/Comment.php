<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;

use Railken\Laravel\Manager\Tests\Core\Article\Article;
use Railken\Laravel\Manager\Tests\User\User;

class Comment extends Model implements EntityContract
{

	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['content'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * Get the author that wrote the article
	 */
	public function author()
	{
		return $this->belongsTo(User::class, 'author_id');
	}

	/**
	 * Get the article of this comment
	 */
	public function article()
	{
		return $this->belongsTo(Article::class, 'article_id');
	}
}
