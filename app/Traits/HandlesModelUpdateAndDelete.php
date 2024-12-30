<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HandlesModelUpdateAndDelete
{
    /**
     * Update the given model instance with provided data.
     *
     * @param \Illuminate\Database\Eloquent\Model $modelInstance
     * @param array $updateData
     * @param string|null $editorId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateModel(Model $modelInstance, array $updateData, ?string $editorId = null)
    {
        // Optionally add an editor ID
        if ($editorId) {
            $updateData['editor_id'] = $editorId;
        }

        // Fill the model with the provided update data
        $modelInstance->fill($updateData);

        // Save the model to the database
        $modelInstance->save();

        // Return the updated model instance
        return $modelInstance;
    }

    /**
     * Update the model and then delete it.
     *
     * @param \Illuminate\Database\Eloquent\Model $modelInstance
     * @param array $updateData
     * @param string|null $editorId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateAndDeleteModel(Model $modelInstance, array $updateData, ?string $editorId = null)
    {
        // Update the model first
        $this->updateModel($modelInstance, $updateData, $editorId);

        // Optionally add an editor ID before deletion
        if ($editorId) {
            $modelInstance->editor_id = $editorId;
            $modelInstance->save();
        }

        // Delete the model
        $modelInstance->delete();

        // Return the deleted model instance
        return $modelInstance;
    }
}
