<?php
/**
 * Contains the Channel class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Channel\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Channel\Contracts\Channel as ChannelContract;

class Channel extends Model implements ChannelContract
{
}
