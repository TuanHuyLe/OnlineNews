<div class="col-md-4">

    <!-- Search Widget -->
    <div class="card my-4">
        <h5 class="card-header">Tìm kiếm</h5>
        <form action="{{route('home.search')}}" id="search_form" method="GET" class="card-body">
            <div class="input-group">
                <input type="text" name="key" class="form-control" placeholder="Tìm kiếm...">
                <span class="input-group-append">
                <button class="btn btn-secondary" type="submit">Tìm!</button>
              </span>
            </div>
        </form>
    </div>

    <!-- Categories Widget -->
    <div class="card my-4">
        <h5 class="card-header">Thể loại</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-unstyled mb-0" id="data-menu-category">
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
