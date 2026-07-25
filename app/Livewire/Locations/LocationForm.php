    <?php

    namespace App\Livewire\Locations;

    use App\Models\Location;
    use Livewire\Component;

    class LocationForm extends Component
    {
        public ?Location $location = null;
        public $parent_id = null;
        public $type = 'alan';
        public $code = '';
        public $name = '';

        public function mount(Location $location = null)
        {
            if ($location && $location->exists) {
                $this->location = $location;
                $this->parent_id = $location->parent_id;
                $this->type = $location->type;
                $this->code = $location->code;
                $this->name = $location->name;
            }
        }

        protected function rules()
        {
            return [
                'parent_id' => 'nullable|exists:locations,id',
                'type' => 'required|string|in:alan,koridor,raf,goz',
                'code' => 'required|string|unique:locations,code,' . optional($this->location)->id,
                'name' => 'required|string|max:255',
            ];
        }

        public function save()
        {
            $this->validate();

            Location::updateOrCreate(
                ['id' => optional($this->location)->id],
                [
                    'parent_id' => $this->parent_id ?: null,
                    'type' => $this->type,
                    'code' => $this->code,
                    'name' => $this->name,
                ]
            );

            return $this->redirect(route('locations.index'), navigate: true);
        }

        public function render()
        {
            $parentLocations = Location::where('type', '!=', 'goz')->get();
            return view('livewire.locations.location-form', compact('parentLocations'));
        }
    }