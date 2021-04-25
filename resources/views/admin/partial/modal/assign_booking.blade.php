<div class="modal fade" id="modalAssignBooking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md action" id="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title">Phân công đơn hàng</span>
                </h4>
            </div>
            {{ Form::open(['url' => '', 'method' => '', 'enctype' => 'multipart/form-data']) }}
            <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}">
            <input type="hidden" id="id" name="id">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        
                        <div class="col-lg-6">
                            <label>Chọn Shipper</label>
                            {{ Form::select('province_id_to', \App\Models\User::getUserOption('shipper') , old('province_id_to'),
                             ['class' => 'form-control', 'style' => 'width:100%', 'id'=>'province_to', 'onchange'=>'loadDistrictTo()']) }}
                            @if (isset($errors) && $errors->has('province_id_to'))
                                @foreach ($errors->get('province_id_to') as $error)
                                    <span style="color: red" class="help-block">{!! $error !!}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Chọn hình thức vận chuyển</label>
                            <select class="form-control">
                                <option value="1">Lấy hàng</option>
                                <option value="2">Giao hàng</option>
                                <option value="3">Vừa lấy vừa giao hàng</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="btnSave"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                    Lưu</button>
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"
                        aria-hidden="true"></i> Bỏ qua</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>


