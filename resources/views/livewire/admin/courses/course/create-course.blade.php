<div>
    <livewire:comp.breadcumb title="COURSES" section="Admin" sub="Courses" action="Course Add">
        <div>
            <!--! Start:: Content Section !-->
            <div class="edash-page-container container-xxl" id="edash-page-container">
                <!--! Start:: Content Section !-->
                <div class="edash-content-section">
                    <div class="d-flex gap-3 gap-md-4">

                        <!-- End:: edash-settings-aside -->
                        <!-- Start:: edash-settings-content -->
                        <div class="edash-settings-content card w-100 overflow-hidden">
                            <!--! Start:: settings-content-header !-->

                            <!--! End:: settings-content-header !-->
                            <div class="card-body">
                                <div class="col-6">
                                    @if (session()->has('message'))
                                        <div class="alert alert-success">
                                            {{ session('message') }}
                                        </div>
                                    @endif
                                </div>
                                <form wire:submit="saveCourse">

                                    <!--! End:: profile-avatar !-->
                                    <hr class="my-12 border-top-dashed" />
                                    <!--! Start:: personal-info !-->
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Course Name</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" wire:model='name'
                                                placeholder="Course Name" />
                                        </div>
                                        @error('name')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Category Name</label>
                                        </div>
                                        <div class="col-lg-2 mb-2 mb-md-0">
                                            <select class="form-select" wire:model='category_id'>
                                                <option>--select--</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->cat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('category_id')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">With /Without Disount</label>
                                        </div>
                                        <div class="col-lg-2 mb-2 mb-md-0">
                                            <select class="form-select" wire:model='is_discounted'
                                                wire:change='changeDiscounted'>
                                                <option>--select--</option>
                                                <option value="1">Discounted
                                                </option>
                                                <option value="2">No Discount
                                                </option>
                                            </select>
                                        </div>
                                        @error('category_id')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Description</label>
                                        </div>
                                        <div class="col-md-9" wire:ignore>
                                            <textarea name="summernoteBasic" id="summernoteBasic">{!! $description !!}</textarea>
                                            <textarea class="d-none" id="description_text" wire:model.lazy='description_text'></textarea>

                                        </div>
                                        @error('description')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Thumbnail</label>

                                        </div>
                                        <div class="col-md-9">

                                            <input type="file" class="form-control" wire:model='thumbnail'
                                                placeholder="Course Name" />
                                            @if ($thumbnail)
                                                <div class="col-md-9 mt-2">
                                                    <img style="width : 100px;" src="{{ $thumbnail->temporaryUrl() }}">

                                                </div>
                                            @endif
                                        </div>
                                        @error('thumbnail')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Course Price</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" wire:model='course_price'
                                                placeholder="Course Price" />
                                        </div>
                                        @error('course_price')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    @if ($is_discounted == 1)
                                        <div class="row g-4 mb-4">
                                            <div class="col-md-3">
                                                <label class="fw-semibold text-muted">Display Price</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" wire:model='display_price'
                                                    placeholder="Display Price" />
                                            </div>
                                            @error('display_price')
                                                <div style="color: red">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="row g-4 mb-4">
                                            <div class="col-md-3">
                                                <label class="fw-semibold text-muted">Discount %</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" wire:model='discount'
                                                    placeholder="Discount %" />
                                            </div>
                                            @error('discount')
                                                <div style="color: red">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Referer Commision</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" wire:model='referer_commission'
                                                placeholder="Referer Commision" />
                                        </div>
                                        @error('referer_commission')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Course Points</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" wire:model='course_point'
                                                placeholder="Course Points" />
                                        </div>
                                        @error('course_point')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Installment 1</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" wire:model='installment_1'
                                                placeholder="Installment 1" />
                                        </div>
                                        @error('installment_1')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Installment 2</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" wire:model='installment_2'
                                                placeholder="Installment 2" />
                                        </div>
                                        @error('installment_2')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3">
                                            <label class="fw-semibold text-muted">Website Availability</label>
                                        </div>
                                        <div class="col-md-9">


                                            <div class="form-check mb-3">
                                                <input class="form-check-input form-check-soft-primary"
                                                    wire:model='has_website' type="checkbox" value=""
                                                    id="flexCheckSoftPrimaryCheckedDisabled" checked />
                                                <label class="form-check-label"
                                                    for="flexCheckSoftPrimaryCheckedDisabled"></label>
                                            </div>
                                        </div>
                                        @error('has_website')
                                            <div style="color: red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>


                                    <hr class="my-12 border-top-dashed" />
                                    <!--! Start:: action-button !-->
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-9">
                                            <a href="javascript:void(0);" wire:click='clear'
                                                class="btn btn-light text-danger">Discard</a>
                                            <button class="btn btn-primary" type="submit">
                                                SAVE
                                            </button>
                                        </div>
                                    </div>
                                    <!--! End:: action-button !-->

                                </form>
                            </div>
                        </div>
                        <!-- End:: edash-settings-content  -->
                    </div>
                </div>
                <!--! End:: Content Section !-->
            </div>
            <!--! End:: Content Section !-->
        </div>
</div>
<script>
    function getContent(contents) {
        @this.set('description_text', contents)

    }
</script>
