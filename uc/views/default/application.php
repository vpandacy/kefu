<style>
    body {
        margin: 0;
        padding: 0;
    }
    #select_application{
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: #e6e7ec;
    }
    #select_application .application_content {
        width: 100%;
        height: 100%;
    }
    #select_application .application_title {
        text-align: center;
        font-size: 35px;
        margin: 60px 0;
    }
    #select_application .application_ul {
        max-width: 1280px;
        margin: 0 auto;
        padding-left: 32px;
        height: 770px;
        overflow-y: auto;
    }
    #select_application .application_ul .application_li {
        width: 285px;
        height: 280px;
        background-color: #fff;
        box-shadow: 0 6px 8px 0 rgba(9,9,9, .04);
        margin: 0 10px 20px;
        float: left;
        position: relative;
        overflow: hidden;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
    }
    #select_application .application_ul .application_li .application_name {
        font-size: 31px;
        line-height: 280px;
    }
    #select_application .application_ul .application_li:hover {
        background: #fdfdfd;
    }
</style>
<div id="select_application">
    <div class="application_content">
        <div class="application_title">选择应用</div>
        <ul class="application_ul">
            <li class="application_li">
                <div class="application_name">UC平台</div>
            </li>
            <li class="application_li">
                <div class="application_name">联展平台</div>
            </li>
            <li class="application_li">
                <div class="application_name">联展平台</div>
            </li>
            <li class="application_li">
                <div class="application_name">联展平台</div>
            </li>
        </ul>
    </div>
</div>