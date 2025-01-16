<?php

namespace App\Livewire\Admin\Courses\Course;

use App\Models\Course;
use App\Models\CourseCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCourse extends Component
{
    use WithFileUploads;

    public $categories;
    public $name, $category_id, $description,
        $thumbnail, $display_price, $discount,
        $referer_commission, $course_point, $installment_1,
        $installment_2, $has_website, $course_price;
    public $is_discounted = false;
    public $description_text;

    public function mount()
    {
        $this->categories = CourseCategory::all();
    }

    public function render()
    {
        return view('livewire.admin.courses.course.create-course');
    }

    public function saveCourse()
    {

        $this->validate();

        try {

            DB::beginTransaction();

            $course = new Course();

            $course->name = $this->name;
            $course->category_id = $this->category_id;
            $course->description = $this->description_text;
            $course->display_price = $this->display_price;
            $course->discount = $this->discount;
            $course->referer_commission = $this->referer_commission;
            $course->course_point = $this->course_point;
            $course->course_price = $this->course_price;
            $course->installment_1 = $this->installment_1;
            $course->installment_2 = $this->installment_2;
            $course->has_website = $this->has_website;
            $course->discount_status = $this->is_discounted;
            $course->thumbnail = $this->thumbnail->store('public/photos');
            $course->thumbnail = str_replace("public/", "", $course->thumbnail);

            $course->save();

            DB::commit();

            $this->dispatch('success_alert', ['title' => 'Course successfully Saved']);
            return redirect()->route('admin.course.index');
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('saveCourse :: ' . $e->getMessage());
        }
    }

    protected function rules()
    {
        return  [
            'name' => 'required',
            'category_id' => 'required',
            'description_text' => 'required',
            'thumbnail' => 'required|image|max:1024',
            // 'display_price' => 'required',
            // 'discount' => 'required',
            'referer_commission' => 'required',
            'course_price' => 'required',
            'course_point' => 'required',
            'installment_1' => 'required',
            'installment_2' => 'required',
            'has_website' => 'required',

        ];
    }

    public function clear()
    {
        // $this->cat_name = "";
        // $this->cat_name = "";
        // $this->cat_name = "";
        // $this->cat_name = "";
        // $this->cat_name = "";
        // $this->cat_name = "";
        // $this->cat_name = "";
    }

    public function changeDiscounted()
    {
        // dd($this->is_discounted);
    }
}
