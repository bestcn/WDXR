<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <h2><i data-toggle="tooltip" data-placement="right" title="搜索结果包含所有入驻或未入驻的企业" class="fa fa-info-circle"></i> {{ page.total_items | default(0) }} 条相关企业信息 : <span class="text-navy">“{{ request.get('top-search', 'trim') }}”</span></h2>

                <div class="search-form">
                    <form action="{{ url('admin/tools/search') }}" method="get">
                        <div class="input-group">
                            <input type="text" placeholder="搜索企业" name="top-search" value="{{ request.get('top-search', 'trim') }}" class="form-control input-lg">
                            <div class="input-group-btn">
                                <button class="btn btn-lg btn-primary" type="submit">
                                    搜索
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="hr-line-dashed"></div>

                {% if page.items is defined %}
                    {% if page.total_items == 0 %}
                        <div class="h-300 search-result">
                            没有搜索到相关企业
                        </div>
                    {% else %}
                        {% for company in page.items %}
                            <div class="search-result">
                                <h3 class="p-xxs"><a href="{{ url('admin/companys/info/'~company.id) }}">{{ company.name }}</a></h3>
                                <table class="table small m-b-xs">
                                    <tbody>
                                    <tr>
                                        <th>统一社会信用代码</th><td>{{ company.licence_num }}</td>
                                        <th>法人</th><td>{{ company.legal_name | default('无') }}</td>
                                        <th>企业类型</th><td>{{ company.type == 1 ? '个体工商户' : '非个体工商户' | default('无') }}</td>
                                        <th>企业地址</th><td>{{ get_address(company.province, company.city, company.district, company.address) }}</td>
                                        <th><i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="企业信息录入到业务系统中的时间"></i> 添加时间</th><td>{{ company.time }}</td>
                                    </tr>
                                    <tr>
                                        <th>所属业务员</th><td>{{ company.admin_name |default('无') }}</td>
                                        <th>营业范围</th><td colspan="9">{{ company.scope | default('无') }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="hr-line-dashed"></div>
                        {% endfor %}
                        <div class="text-center">
                            <div class="btn-group">
                                <a href="{{ url('admin/tools/search?page='~page.first~'&top-search='~request.get('top-search')) }}" class="btn btn-white" type="button"><i class="fa fa-angle-double-left"></i> 第一页</a>
                                <a href="{{ url('admin/tools/search?page='~page.before~'&top-search='~request.get('top-search')) }}" class="btn btn-white" type="button"><i class="fa fa-angle-left"></i> 前一页</a>
                                <a href="{{ url('admin/tools/search?page='~page.next~'&top-search='~request.get('top-search')) }}" class="btn btn-white" type="button">后一页 <i class="fa fa-angle-right"></i></a>
                                <a href="{{ url('admin/tools/search?page='~page.last~'&top-search='~request.get('top-search')) }}" class="btn btn-white" type="button">最后一页 <i class="fa fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    {% endif %}
                {% else %}
                    <div class="h-300 search-result">
                        请输入关键字
                    </div>
                {% endif %}

            </div>
        </div>
    </div>
</div>
