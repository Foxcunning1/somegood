<?php
namespace Mobile\Controller;
use Think\Controller;
/**统计网站访问
 *
 */
class StatsController extends Controller
{
    /**统计网站访问
     * */
    public function visitStatsAction(){
        visit_stats();
    }
}

?>
