<div class="col-md-4">

    <!-- Search Widget -->
    <div class="card my-4">
        <h5 class="card-header">Tìm kiếm</h5>
        <div class="card-body">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm...">
                <span class="input-group-append">
                <button class="btn btn-secondary" type="button">Tìm!</button>
              </span>
            </div>
        </div>
    </div>

    <!-- Categories Widget -->
    <div class="card my-4">
        <h5 class="card-header">Thể loại</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-unstyled mb-0">
                        @foreach($categoryItems as $item)
                            <li>
                                <a href="{{$item->code}}">{{$item->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
